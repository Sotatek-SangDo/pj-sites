<?php

/**
 * BetterMag Google+ Widget
 */
class BM_Google_Plus_Widget extends BF_Widget{

    /**
     * Register widget with WordPress.
     */
    function __construct(){

        // Back end form fields
        $this->fields = array(
            array(
                'name'          =>  __( 'Title:', 'better-studio' ),
                'attr_id'       =>  'title',
                'type'          =>  'text',
                'section_class' =>  'widefat',
            ),
            array(
                'name'          =>  __( 'Type:', 'better-studio' ),
                'attr_id'       =>  'type',
                'type'          =>  'select',
                'options'       =>  array(
                    'profile'   =>  __( 'Profile', 'better-studio' ),
                    'page'      =>  __( 'Page', 'better-studio' ),
                    'community' =>  __( 'Community', 'better-studio' ),
                ),
                'section_class' =>  'widefat',
            ),
            array(
                'name'          =>  __( 'Google+ Page URL:', 'better-studio' ),
                'attr_id'       =>  'url',
                'type'          =>  'text',
                'section_class' =>  'widefat',
            ),
            array(
                'name'          =>  __( 'Width:', 'better-studio' ),
                'attr_id'       =>  'width',
                'type'          =>  'text',
                'section_class' =>  'widefat',
            ),
            array(
                'name'          =>  __( 'Color Scheme:', 'better-studio' ),
                'attr_id'       =>  'scheme',
                'type'          =>  'select',
                'options'       =>  array(
                    'light'     =>  __( 'Light', 'better-studio' ),
                    'dark'      =>  __( 'Dark', 'better-studio' ),
                ),
                'section_class' =>  'widefat',
            ),
            array(
                'name'          =>  __( 'Layout:', 'better-studio' ),
                'attr_id'       =>  'layout',
                'type'          =>  'select',
                'options'       =>  array(
                    'portrait'  =>  __( 'Portrait', 'better-studio' ),
                    'landscape' =>  __( 'Landscape', 'better-studio' ),
                ),
                'section_class' =>  'widefat',
            ),
            array(
                'name'          =>  __( 'Cover:', 'better-studio' ),
                'attr_id'       =>  'cover',
                'type'          =>  'select',
                'options'       =>  array(
                    'show'      =>  __( 'Show', 'better-studio' ),
                    'hide'      =>  __( 'Hide', 'better-studio' ),
                ),
                'section_class' =>  'widefat',
            ),
            array(
                'name'          =>  __( 'Tagline:', 'better-studio' ),
                'attr_id'       =>  'tagline',
                'type'          =>  'select',
                'options'       =>  array(
                    'show'      =>  __( 'Show', 'better-studio' ),
                    'hide'      =>  __( 'Hide', 'better-studio' ),
                ),
                'section_class' =>  'widefat',
            ),
            array(
                'name'          =>  __( 'Language:', 'better-studio' ),
                'attr_id'       =>  'lang',
                'type'          =>  'select',
                'options'       =>  array(
                    'af'      =>  __( 'Afrikaans', 'better-studio' ),
                    'am'      =>  __( 'Amharic', 'better-studio' ),
                    'ar'      =>  __( 'Arabic', 'better-studio' ),
                    'eu'      =>  __( 'Basque', 'better-studio' ),
                    'bn'      =>  __( 'Bengali', 'better-studio' ),
                    'bg'      =>  __( 'Bulgarian', 'better-studio' ),
                    'ca'      =>  __( 'Catalan', 'better-studio' ),
                    'zh-HK'   =>  __( 'Chinese (Hong Kong)', 'better-studio' ),
                    'zh-CN'   =>  __( 'Chinese (Simplified)', 'better-studio' ),
                    'zh-TW'   =>  __( 'Chinese (Traditional)', 'better-studio' ),
                    'hr'      =>  __( 'Croatian', 'better-studio' ),
                    'cs'      =>  __( 'Czech', 'better-studio' ),
                    'da'      =>  __( 'Danish', 'better-studio' ),
                    'nl'      =>  __( 'Dutch', 'better-studio' ),
                    'en-GB'   =>  __( 'English (UK)', 'better-studio' ),
                    'en-US'   =>  __( 'English (US)', 'better-studio' ),
                    'et'      =>  __( 'Estonian', 'better-studio' ),
                    'fil'     =>  __( 'Filipino', 'better-studio' ),
                    'fi'      =>  __( 'Finnish', 'better-studio' ),
                    'fr'      =>  __( 'French', 'better-studio' ),
                    'fr-CA'   =>  __( 'French (Canadian)', 'better-studio' ),
                    'gl'      =>  __( 'Galician', 'better-studio' ),
                    'de'      =>  __( 'German', 'better-studio' ),
                    'el'      =>  __( 'Greek', 'better-studio' ),
                    'gu'      =>  __( 'Gujarati', 'better-studio' ),
                    'iw'      =>  __( 'Hebrew', 'better-studio' ),
                    'hi'      =>  __( 'Hindi', 'better-studio' ),
                    'hu'      =>  __( 'Hungarian', 'better-studio' ),
                    'is'      =>  __( 'Icelandic', 'better-studio' ),
                    'id'      =>  __( 'Indonesian', 'better-studio' ),
                    'it'      =>  __( 'Italian', 'better-studio' ),
                    'ja'      =>  __( 'Japanese', 'better-studio' ),
                    'kn'      =>  __( 'Kannada', 'better-studio' ),
                    'ko'      =>  __( 'Korean', 'better-studio' ),
                    'lv'      =>  __( 'Latvian', 'better-studio' ),
                    'lt'      =>  __( 'Lithuanian', 'better-studio' ),
                    'ms'      =>  __( 'Malay', 'better-studio' ),
                    'ml'      =>  __( 'Malayalam', 'better-studio' ),
                    'mr'      =>  __( 'Marathi', 'better-studio' ),
                    'no'      =>  __( 'Norwegian', 'better-studio' ),
                    'fa'      =>  __( 'Persian', 'better-studio' ),
                    'pl'      =>  __( 'Polish', 'better-studio' ),
                    'pt-BR'   =>  __( 'Portuguese (Brazil)', 'better-studio' ),
                    'pt-PT'   =>  __( 'Portuguese (Portugal)', 'better-studio' ),
                    'ro'      =>  __( 'Romanian', 'better-studio' ),
                    'ru'      =>  __( 'Russian', 'better-studio' ),
                    'sr'      =>  __( 'Serbian', 'better-studio' ),
                    'sk'      =>  __( 'Slovak', 'better-studio' ),
                    'sl'      =>  __( 'Slovenian', 'better-studio' ),
                    'es'      =>  __( 'Spanish', 'better-studio' ),
                    'es-419'  =>  __( 'Spanish (Latin America)', 'better-studio' ),
                    'sw'      =>  __( 'Swahili', 'better-studio' ),
                    'sv'      =>  __( 'Swedish', 'better-studio' ),
                    'ta'      =>  __( 'Tamil', 'better-studio' ),
                    'te'      =>  __( 'Telugu', 'better-studio' ),
                    'th'      =>  __( 'Thai', 'better-studio' ),
                    'tr'      =>  __( 'Turkish', 'better-studio' ),
                    'uk'      =>  __( 'Ukrainian', 'better-studio' ),
                    'ur'      =>  __( 'Urdu', 'better-studio' ),
                    'vi'      =>  __( 'Vietnamese', 'better-studio' ),
                    'zu'      =>  __( 'Zulu', 'better-studio' ),
                ),
                'section_class' =>  'widefat',
            ),

        );

        parent::__construct(
            'bm-google-plus',
            __( 'BetterStudio - Google+ Badge Box', 'better-studio' ),
            array( 'description' => __( 'Adds a beautiful Google Plus badge widget.', 'better-studio' ) )
        );
    }
}