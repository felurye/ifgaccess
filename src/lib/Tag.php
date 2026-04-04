<?php
define('TAG_FILE', __DIR__ . '/../tag.txt');

function saveTag(string $tag): void
{
    file_put_contents(TAG_FILE, $tag);
}

function clearTag(): void
{
    file_put_contents(TAG_FILE, '');
}

function getLastTag(): string
{
    if (!file_exists(TAG_FILE)) {
        return '';
    }
    return trim(file_get_contents(TAG_FILE));
}
