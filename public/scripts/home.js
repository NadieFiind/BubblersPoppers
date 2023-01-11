/* eslint-disable func-style, no-unused-vars, no-undef */

function makeBubble() {
    circle(random(0, width), random(0, height), 30);
}

function setup() {
    const canvas = createCanvas(windowWidth, windowHeight);
    canvas.parent("canvas");

    background(0);
    fill(255);

    for (let i = 0; i < bubbles; i += 1) {
        makeBubble();
    }
}

function mouseClicked() {
    bubbles += 1;
    makeBubble();
}
