<?php declare(strict_types=1);

function render(): void
{
    echo <<<HTML
    <div id="canvas"></div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/p5.js/1.5.0/p5.min.js"
        integrity="sha512-WJXVjqeINVpi5XXJ2jn0BSCfp0y80IKrYh731gLRnkAS9TKc5KNt/OfLtu+fCueqdWniouJ1ubM+VI/hbo7POQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        let bubbles = 10;
    </script>

    <script src="/public/scripts/home.js"></script>
    HTML;
}
