<?php
return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([

        // --- Core PHPDoc rules ---
        'phpdoc_add_missing_param_annotation'       => ['only_untyped' => false],
        'phpdoc_align'                              => ['align' => 'left'],
        'phpdoc_annotation_without_dot'             => true,
        'phpdoc_indent'                             => true,
        'phpdoc_line_span'                          => [
            'const'    => 'multi',
            'method'   => 'multi',
            'property' => 'multi',
        ],
        'phpdoc_no_access'                          => true,
        'phpdoc_no_alias_tag'                       => true,
        'phpdoc_no_empty_return'                    => true,
        'phpdoc_no_package'                         => true,
        'phpdoc_no_useless_inheritdoc'              => true,
        'phpdoc_order'                              => [
            'order' => ['param', 'throws', 'return']
        ],
        'phpdoc_return_self_reference'              => true,
        'phpdoc_scalar'                             => true,
        'phpdoc_separation'                         => true,
        'phpdoc_single_line_var_spacing'            => true,
        'phpdoc_summary'                            => true,
        'phpdoc_to_comment'                         => false,  // keep docblocks as docblocks
        'phpdoc_trim'                               => true,
        'phpdoc_trim_consecutive_blank_line_separation' => true,
        'phpdoc_types'                              => true,
        'phpdoc_types_order'                        => [
            'null_adjustment' => 'always_last',
            'sort_algorithm'  => 'none',
        ],
        'phpdoc_var_annotation_correct_order'       => true,
        'phpdoc_var_without_name'                   => true,
        'no_empty_phpdoc'                           => true,
        'no_blank_lines_after_phpdoc'               => true,

        // --- Method/class doc helpers ---
        'general_phpdoc_annotation_remove'         => [
            'annotations' => ['author', 'package', 'subpackage'] // remove noise
        ],
        'general_phpdoc_tag_rename'                => [
            'replacements' => ['inheritDocs' => 'inheritDoc']
        ],

    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in(__DIR__)   // <-- adjust to your folder(s)
            ->exclude('vendor')
            ->exclude('tests')       // remove this if you want tests documented too
            ->name('*.php')
    );