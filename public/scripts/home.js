/* eslint-disable func-style, no-unused-vars, no-undef */
let bgImage = null;
let bubbleImage = null;

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
        this.r = random(10, 30);
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
        push();
        imageMode(CENTER);
        image(bubbleImage, this.x, this.y, this.r * 2, this.r * 2);
        pop();
    }

    move() {
        this.x += random(-1, 1);
        this.y += random(-3, 0);

        if (this.y + this.r < 0) {
            this.y = height + this.r;
        }

        this.x = constrain(this.x, 0, width);
    }

    isTouching() {
        return dist(mouseX, mouseY, this.x, this.y) < this.r;
    }
}

function preload() {
    bgImage = loadImage("/public/images/bg.jpg");
    bubbleImage = loadImage("/public/images/favicon.ico");
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
    background(bgImage);

    for (const bubble of Bubble.bubbles) {
        bubble.move();
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
