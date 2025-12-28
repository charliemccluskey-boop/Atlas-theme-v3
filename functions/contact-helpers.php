<?php
/**
 * Reusable helpers for contact templates.
 */

if ( ! function_exists( 'atlas_get_contact_field' ) ) {
    /**
     * Retrieve a contact field value with ACF support and post meta fallback.
     *
     * @param string   $meta_key Field/meta key.
     * @param int|null $post_id  Post ID, defaults to current post.
     * @param mixed    $default  Default value when no data is available.
     *
     * @return mixed
     */
    function atlas_get_contact_field( $meta_key, $post_id = null, $default = '' ) {
        $post_id = $post_id ? $post_id : get_the_ID();

        $value = null;

        if ( function_exists( 'get_field' ) ) {
            $value = get_field( $meta_key, $post_id );
        }

        if ( null === $value || '' === $value ) {
            $meta_value = get_post_meta( $post_id, $meta_key, true );

            if ( '' !== $meta_value || '0' === $meta_value ) {
                $value = $meta_value;
            }
        }

        if ( null === $value || '' === $value ) {
            $value = $default;
        }

        return $value;
    }
}

if ( ! function_exists( 'atlas_contact_status_classes' ) ) {
	/**
	 * Map contact status labels to Tailwind badge classes.
	 *
	 * @param string $status Status label.
	 *
	 * @return string
	 */
	function atlas_contact_status_classes( $status ) {
		$status = atlas_contact_first_value( $status );
		$status = strtolower( (string) $status );

        switch ( $status ) {
            case 'active':
                return 'bg-green-100 text-green-800';
            case 'prospective':
            case 'prospect':
                return 'bg-yellow-100 text-yellow-800';
            case 'inactive':
                return 'bg-gray-200 text-gray-700';
            default:
                return 'bg-blue-100 text-blue-800';
        }
    }
}

if ( ! function_exists( 'atlas_contact_linked_text' ) ) {
	/**
	 * Display a linked value if a matching post ID exists, otherwise plain text.
	 *
	 * @param string|int $value Value to display.
	 *
	 * @return string
	 */
	function atlas_contact_linked_text( $value ) {
		$value = atlas_contact_first_value( $value );

		if ( ! $value ) {
			return '-';
		}

        if ( is_numeric( $value ) ) {
            $label = get_the_title( (int) $value );
            $url   = get_permalink( (int) $value );

            if ( $url ) {
                return sprintf(
                    '<a href="%1$s" class="text-blue-600 hover:text-blue-800">%2$s</a>',
                    esc_url( $url ),
                    esc_html( $label )
                );
            }
        }

        return esc_html( (string) $value );
    }
}

if ( ! function_exists( 'atlas_contact_default_text' ) ) {
    /**
     * Return a default dash when the value is empty.
     *
     * @param string $value Value to format.
     *
     * @return string
     */
	function atlas_contact_default_text( $value ) {
		$value = atlas_contact_first_value( $value );

		return $value ? esc_html( $value ) : '-';
	}
}

if ( ! function_exists( 'atlas_contact_format_date' ) ) {
    /**
     * Format a date string into DD MM YYYY.
     *
     * @param string $date_string Date string to format.
     *
     * @return string
     */
	function atlas_contact_format_date( $date_string ) {
		$date_string = atlas_contact_first_value( $date_string );

		if ( ! $date_string ) {
			return '';
        }

        $timestamp = strtotime( (string) $date_string );

        if ( ! $timestamp ) {
            return (string) $date_string;
        }

        return date_i18n( 'd m Y', $timestamp );
	}
}

if ( ! function_exists( 'atlas_contact_first_value' ) ) {
	/**
	 * Normalize values that might be arrays or objects to a single scalar.
	 *
	 * @param mixed $value Value to normalize.
	 *
	 * @return mixed
	 */
	function atlas_contact_first_value( $value ) {
		if ( is_array( $value ) ) {
			$value = reset( $value );
		}

        if ( $value instanceof WP_Post ) {
            return $value->ID;
        }

        if ( $value instanceof WP_Term ) {
            return $value->slug ? $value->slug : $value->name;
        }

        return $value;
	}
}
