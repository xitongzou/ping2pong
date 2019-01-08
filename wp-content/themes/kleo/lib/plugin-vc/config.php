<?php

if ( ! function_exists( 'vc_has_class' ) ) {
    /**
     * Check if element has specific class
     *
     * E.g. f('foo', 'foo bar baz') -> true
     *
     * @param string $class Class to check for
     * @param string $classes Classes separated by space(s)
     *
     * @return boolean
     */
    function vc_has_class($class, $classes)
    {
        return in_array($class, explode(' ', strtolower($classes)));
    }
}

if ( ! function_exists( 'vc_stringify_attributes' ) ) {
    /**
     * Convert array of named params to string version
     * All values will be escaped
     *
     * E.g. f(array('name' => 'foo', 'id' => 'bar')) -> 'name="foo" id="bar"'
     *
     * @param $attributes
     *
     * @return string
     */
    function vc_stringify_attributes($attributes)
    {
        $atts = array();
        foreach ($attributes as $name => $value) {
            $atts[] = $name . '="' . esc_attr($value) . '"';
        }

        return implode(' ', $atts);
    }
}

if (! function_exists('vc_map_get_attributes')) {
    /**
     * @param $tag - shortcode tag
     * @param $atts - shortcode attributes
     *
     * @return array - return merged values with provided attributes ( 'a'=>1,'b'=>2 + 'b'=>3,'c'=>4 == 'a'=>1,'b'=>3 )
     *
     * @see vc_shortcode_attribute_parse - return union of provided attributes ( 'a'=>1,'b'=>2 + 'b'=>3,'c'=>4 == 'a'=>1,
     *     'b'=>3, 'c'=>4 )
     */
    function vc_map_get_attributes( $tag, $atts = array() ) {
        return shortcode_atts( vc_map_get_defaults( $tag ), $atts, $tag );
    }

}