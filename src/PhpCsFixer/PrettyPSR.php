<?php
namespace nochso\Omni\PhpCsFixer;

use Symfony\CS\Config\Config;
use Symfony\CS\FixerInterface;

/**
 * PrettyPSR lies inbetween FixerInterface's PSR2 and Symfony level.
 *
 * Create a new file `.php_cs` in your project root:
 *
 * ```
 * <?php
 * require_once 'vendor/autoload.php';
 * use nochso\Omni\PhpCsFixer\PrettyPSR;
 * return PrettyPSR::createIn(['src', 'test']);
 * ```
 *
 * Running `php-cs-fixer` will fix all files in folders `src` and `test`.
 *
 * Note that `php-cs-fixer` is not included by default. You have to require it first:
 * ```
 * composer require --dev fabpot/php-cs-fixer
 * ```
 */
class PrettyPSR extends Config
{
    /**
     * @param string|array $dirs
     *
     * @return static
     */
    public static function createIn($dirs)
    {
        $config = new static();
        $config->finder(DefaultFinder::createIn($dirs));
        return $config;
    }

    /**
     * @param string $name
     * @param string $description
     */
    public function __construct($name = self::class, $description = '')
    {
        parent::__construct($name, $description);
        $this->level = FixerInterface::PSR2_LEVEL;
        $this->fixers = $this->getDefaultFixers();
        $this->setUsingCache(true);
    }

    /**
     * @return string[]
     */
    public function getDefaultFixers()
    {
        return [
            '-psr0', // Does not play well with PSR-4
            '-return',
            '-concat_without_spaces',
            '-empty_return',
            'extra_empty_lines',
            'concat_with_spaces',
            'array_element_no_space_before_comma',
            'array_element_white_space_after_comma',
            'double_arrow_multiline_whitespaces',
            'duplicate_semicolon',
            'function_typehint_space',
            'include',
            'list_commas',
            'multiline_array_trailing_comma',
            'namespace_no_leading_whitespace',
            'newline_after_open_tag',
            'new_with_braces',
            'no_blank_lines_after_class_opening',
            'no_blank_lines_before_namespace',
            'no_empty_lines_after_phpdocs',
            'object_operator',
            'operators_spaces',
            'ordered_use',
            'phpdoc_indent',
            'phpdoc_inline_tag',
            'phpdoc_no_access',
            'phpdoc_no_package',
            'phpdoc_order',
            'phpdoc_params',
            'phpdoc_scalar',
            'phpdoc_separation',
            'phpdoc_short_description',
            'phpdoc_to_comment',
            'phpdoc_trim',
            'phpdoc_types',
            'phpdoc_type_to_var',
            'print_to_echo',
            'remove_lines_between_uses',
            'self_accessor',
            'short_array_syntax',
            'single_array_no_trailing_comma',
            'single_quote',
            'spaces_before_semicolon',
            'ternary_spaces',
            'trim_array_spaces',
            'unalign_double_arrow',
            'unalign_equals',
            'unary_operators_spaces',
            'unneeded_control_parentheses',
            'unused_use',
            'whitespacy_lines',
        ];
    }
}
