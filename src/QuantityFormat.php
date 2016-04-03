<?php
namespace nochso\Omni;

/**
 * QuantityFormat formats a string depending on quantity (many, one or zero).
 * 
 * The plural, singular and empty formats of the string can be defined like this:
 *
 * `(plural|singular|zero)`
 * 
 * The singular and zero formats are optional:
 * 
 * ```php
 * QuantityFormat::format('day(s)', 1); // day
 * QuantityFormat::format('day(s)', 0); // days
 * ```
 *
 * If the `zero` format is not defined, the plural form will be used instead.
 * Alternatively you can use an empty string:
 * 
 * ```php
 * QuantityFormat::format('(many|one|)', 0); // empty string
 * ```
 * 
 * Example with all three formats:
 * 
 * ```php
 * QuantityFormat::format('(bugs|bug|no bugs at all)', 5) // bugs
 * QuantityFormat::format('(bugs|bug|no bugs at all)', 1) // bug
 * QuantityFormat::format('(bugs|bug|no bugs at all)', 0) // no bugs at all
 * ```
 */
class QuantityFormat
{
    /**
     * Format a string depending on a quantity.
     * 
     * See the class documentation for defining `$format`.
     * 
     * @param string $format
     * @param string $quantity
     *
     * @return mixed
     */
    public static function format($format, $quantity)
    {
        $quantity = Numeric::ensure($quantity);
        $callback = function ($matches) use ($quantity) {
            // Get available choices
            $choices = preg_split('/\|/', $matches[2]);
            // Assume plural
            $choice = 0;
            // Choose singular
            if ($quantity === 1.0 || $quantity === 1) {
                $choice = 1;
            }
            // Choose zero if it's defined, otherwise keep using plural format
            if (($quantity === 0 || $quantity === 0.0) && isset($choices[2])) {
                $choice = 2;
            }
            return Dot::get($choices, $choice, '');
        };
        $pattern = '/(?<!\\\\)(\\((.+?(?<!\\\\))\\))/';
        return preg_replace_callback($pattern, $callback, $format);
    }
}
