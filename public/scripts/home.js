/* eslint-disable func-style, no-unused-vars, no-undef */

class Bubble {
    static bubbles = [];

    static async make() {
        const res = await fetch("/api/bubbles.php", {"method": "POST"});
        const data = await res.json();
        const bubble = new Bubble(data.id, mouseX, mouseY);

        Bubble.bubbles.push(bubble);
        return bubble;
    }

    constructor(id, x, y) {
        this.id = id;
        this.x = x || random(0, width);
        this.y = y || random(0, height);
        this.r = 15;
    }

    pop() {
        const index = Bubble.bubbles.indexOf(this);
        if (index > -1) {
            Bubble.bubbles.splice(index, 1);
        }

        fetch("/api/bubbles.php", {
            "method": "PATCH",
            "body": this.id
        });
    }

    render() {
        fill(255);
        circle(this.x, this.y, this.r * 2);
    }

    isTouching() {
        return dist(mouseX, mouseY, this.x, this.y) < this.r;
    }
}

async function setup() {
    createCanvas(windowWidth, windowHeight).parent("canvas");

    const res = await fetch("/api/bubbles.php");
    const data = await res.json();

    for (const bubbleData of data) {
        Bubble.bubbles.push(new Bubble(bubbleData.id));
    }
}

function draw() {
    background(0);

    for (const bubble of Bubble.bubbles) {
        bubble.render();
    }
}

function mouseClicked() {
    for (const bubble of Bubble.bubbles) {
        if (bubble.isTouching()) {
            bubble.pop();
            return;
        }
    }

    Bubble.make();
}
