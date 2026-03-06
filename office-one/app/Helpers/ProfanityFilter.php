<?php

namespace App\Helpers;

class ProfanityFilter
{
    /**
     * List of profanity patterns (regex).
     * Each pattern matches common variations of foul words.
     */
    protected static array $patterns = [
        // Common profanity — covers letter substitutions (e.g. f*ck, sh1t, a$$)
        '/\b(f+[\W_]*u+[\W_]*c+[\W_]*k+[\w]*)\b/i',
        '/\b(s+[\W_]*h+[\W_]*[i1!]+[\W_]*t+[\w]*)\b/i',
        '/\b(b+[\W_]*[i1!]+[\W_]*t+[\W_]*c+[\W_]*h+[\w]*)\b/i',
        '/\b(a+[\W_]*s+[\W_]*s+[\W_]*h+[\W_]*[o0]+[\W_]*l+[\W_]*e+[\w]*)\b/i',
        '/\b(d+[\W_]*[a@]+[\W_]*m+[\W_]*n+[\w]*)\b/i',
        '/\b(h+[\W_]*e+[\W_]*l+[\W_]*l+)\b/i',
        '/\b(c+[\W_]*r+[\W_]*a+[\W_]*p+[\w]*)\b/i',
        '/\b(d+[\W_]*[i1!]+[\W_]*c+[\W_]*k+[\w]*)\b/i',
        '/\b(p+[\W_]*[i1!]+[\W_]*s+[\W_]*s+[\w]*)\b/i',
        '/\b(b+[\W_]*[a@]+[\W_]*s+[\W_]*t+[\W_]*[a@]+[\W_]*r+[\W_]*d+[\w]*)\b/i',
        '/\b(w+[\W_]*h+[\W_]*[o0]+[\W_]*r+[\W_]*e+[\w]*)\b/i',
        '/\b(s+[\W_]*l+[\W_]*u+[\W_]*t+[\w]*)\b/i',
        '/\b(a+[\W_]*s+[\W_]*s+)\b/i',
        '/\b(i+[\W_]*d+[\W_]*i+[\W_]*o+[\W_]*t+[\w]*)\b/i',
        '/\b(s+[\W_]*t+[\W_]*u+[\W_]*p+[\W_]*i+[\W_]*d+[\w]*)\b/i',
        '/\b(m+[\W_]*o+[\W_]*r+[\W_]*o+[\W_]*n+[\w]*)\b/i',

        // Filipino profanity
        '/\b(p+[\W_]*u+[\W_]*t+[\W_]*[a@]+[\w]*)\b/i',
        '/\b(g+[\W_]*[a@]+[\W_]*g+[\W_]*[o0]+)\b/i',
        '/\b(t+[\W_]*[a@]+[\W_]*n+[\W_]*g+[\W_]*[i1!]+[\W_]*n+[\W_]*[a@]+)\b/i',
        '/\b(l+[\W_]*[i1!]+[\W_]*n+[\W_]*t+[\W_]*[i1!]+[\W_]*k+)\b/i',
        '/\b(b+[\W_]*[o0]+[\W_]*b+[\W_]*[o0]+)\b/i',
        '/\b(t+[\W_]*[a@]+[\W_]*r+[\W_]*[a@]+[\W_]*n+[\W_]*t+[\W_]*[a@]+[\W_]*d+[\W_]*[o0]+)\b/i',
        '/\b(l+[\W_]*[e3]+[\W_]*c+[\W_]*h+[\W_]*e+)\b/i',
        '/\b(u+[\W_]*l+[\W_]*[o0]+[\W_]*l+)\b/i',
    ];

    /**
     * Filter profanity from text using regex, replacing with asterisks.
     */
    public static function filter(string $text): string
    {
        foreach (self::$patterns as $pattern) {
            $text = preg_replace_callback($pattern, function ($matches) {
                // Replace each character with * except spaces
                return str_repeat('*', strlen($matches[0]));
            }, $text);
        }

        return $text;
    }

    /**
     * Check if text contains profanity.
     */
    public static function containsProfanity(string $text): bool
    {
        foreach (self::$patterns as $pattern) {
            if (preg_match($pattern, $text)) {
                return true;
            }
        }

        return false;
    }
}
