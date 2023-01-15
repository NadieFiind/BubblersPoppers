/* eslint-disable no-undef, no-new */

class Bubble {
    constructor(id, madeAt, poppedAt) {
        this.id = id;
        this.madeAt = new Date(madeAt);
        this.madeAt = new Date(
            this.madeAt.getFullYear(),
            this.madeAt.getMonth(),
            this.madeAt.getDate(),
            this.madeAt.getHours()
        );
        this.poppedAt = poppedAt === null ? null : new Date(poppedAt);

        if (this.poppedAt !== null) {
            this.poppedAt = new Date(
                this.poppedAt.getFullYear(),
                this.poppedAt.getMonth(),
                this.poppedAt.getDate(),
                this.poppedAt.getHours()
            );
        }
    }
}

const fetchData = async () => {
    const res = await fetch("/api/bubbles.php?include_popped=1");
    const data = await res.json();
    const bubbles = {
        "made": {},
        "popped": {}
    };

    for (const key in data) {
        if (key in data) {
            const bubble = new Bubble(data[key].id, data[key].made_at, data[key].popped_at);

            if (bubble.madeAt in bubbles.made) {
                bubbles.made[bubble.madeAt] += 1;
            } else {
                bubbles.made[bubble.madeAt] = 1;
            }

            if (bubble.poppedAt !== null) {
                if (bubble.poppedAt in bubbles.popped) {
                    bubbles.popped[bubble.poppedAt] += 1;
                } else {
                    bubbles.popped[bubble.poppedAt] = 1;
                }
            }
        }
    }

    return bubbles;
};

const sortByProperty = (propertyName) => {
    let prop = propertyName;
    let sortOrder = 1;

    if (prop[0] === "-") {
        sortOrder = -1;
        prop = prop.substr(1);
    }

    return (a, b) => {
        // eslint-disable-next-line no-nested-ternary
        const result = a[prop] < b[prop] ? -1 : a[prop] > b[prop] ? 1 : 0;
        return result * sortOrder;
    };
};

(async () => {
    const res = await fetch("/api/bubbles.php?length=1");
    const bubblesCount = (await res.json()).length;
    document.querySelector(".bubbles-count").textContent = `Current Bubbles Count: ${bubblesCount}`;

    const bubbles = await fetchData();
    new Chart(
        document.getElementById("chart"),
        {
            "type": "line",
            "data": {
                "datasets": [
                    {
                        "label": "Bubbles Made",
                        "data": Object.keys(bubbles.made).map((key) => ({
                            "x": key.split(" ").
                                splice(0, 5).
                                splice(-4).
                                join(" ").
                                slice(0, 14),
                            "y": bubbles.made[key]
                        })).
                            sort(sortByProperty("x"))
                    },
                    {
                        "label": "Bubbles Popped",
                        "data": Object.keys(bubbles.popped).map((key) => ({
                            "x": key.split(" ").
                                splice(0, 5).
                                splice(-4).
                                join(" ").
                                slice(0, 14),
                            "y": bubbles.popped[key]
                        })).
                            sort(sortByProperty("x"))
                    }
                ]
            },
            "options": {
                "responsive": true,
                "maintainAspectRatio": false
            }
        }
    );
})();
