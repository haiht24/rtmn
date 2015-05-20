<?php
/**
 * Define the shortcode parameters
 *
 * @package    Theme_Junkie_Shortcodes
 * @since      0.1.0
 * @author     Theme Junkie
 * @copyright  Copyright (c) 2014, Theme Junkie & ThemeZilla
 * @license    http://www.gnu.org/licenses/gpl-2.0.html
 */

/* Button Config --- */

$junkie_shortcodes['button'] = array(
	'title'    => __( 'Button', 'junkie' ),
	'id'       => 'junkie-button-shortcode',
	'template' => '[junkie-button {{attributes}}] {{content}} [/junkie-button]',
	'params'   => array(
		'url'  => array(
			'std'   => 'http://example.com',
			'type'  => 'text',
			'label' => __( 'Button URL', 'junkie' ),
			'desc'  => __( 'Add the button\'s url eg http://example.com', 'junkie' )
		),
		'style' => array(
			'type'    => 'select',
			'label'   => __( 'Button Style', 'junkie' ),
			'desc'    => __( 'Select the button\'s style, ie the button\'s colour', 'junkie' ),
			'options' => array(
				'grey'       => __( 'Grey', 'junkie' ),
				'black'      => __( 'Black', 'junkie' ),
				'green'      => __( 'Green', 'junkie' ),
				'light-blue' => __( 'Light Blue', 'junkie' ),
				'blue'       => __( 'Blue', 'junkie' ),
				'red'        => __( 'Red', 'junkie' ),
				'orange'     => __( 'Orange', 'junkie' ),
				'purple'     => __( 'Purple', 'junkie' )
			)
		),
		'size' => array(
			'type'    => 'select',
			'label'   => __( 'Button Size', 'junkie' ),
			'desc'    => __( 'Select the button\'s size', 'junkie' ),
			'options' => array(
				'small'  => __( 'Small', 'junkie' ),
				'medium' => __( 'Medium', 'junkie' ),
				'large'  => __( 'Large', 'junkie' )
			)
		),
		'type' => array(
			'type'    => 'select',
			'label'   => __( 'Button Type', 'junkie' ),
			'desc'    => __( 'Select the button\'s type', 'junkie' ),
			'options' => array(
				'round'  => __( 'Round', 'junkie' ),
				'square' => __( 'Square', 'junkie' )
			)
		),
		'target' => array(
			'type'    => 'select',
			'label'   => __( 'Button Target', 'junkie' ),
			'desc'    => __( 'Set the browser behavior for the click action.', 'junkie' ),
			'options' => array(
				'_self'  => __( 'Same window', 'junkie' ),
				'_blank' => __( 'New window', 'junkie' )
			)
		),
		'content' => array(
			'std'   => __( 'Button Text', 'junkie' ),
			'type'  => 'text',
			'label' => __( 'Button\'s Text', 'junkie' ),
			'desc'  => __( 'Add the button\'s text', 'junkie' ),
		)
	)
);

/* Alert Config --- */

$junkie_shortcodes['alert'] = array(
	'title'    => __( 'Alert', 'junkie' ),
	'id'       => 'junkie-alert-shortcode',
	'template' => '[junkie-alert {{attributes}}] {{content}} [/junkie-alert]',
	'params'   => array(
		'style' => array(
			'type'  => 'select',
			'label' => __( 'Alert Style', 'junkie' ),
			'desc'  => __( 'Select the alert\'s style, ie the alert colour', 'junkie' ),
			'options' => array(
				'white'  => __( 'White', 'junkie' ),
				'grey'   => __( 'Grey', 'junkie' ),
				'red'    => __( 'Red', 'junkie' ),
				'yellow' => __( 'Yellow', 'junkie' ),
				'green'  => __( 'Green', 'junkie' )
			)
		),
		'content' => array(
			'std'   => __( 'Your Alert!', 'junkie' ),
			'type'  => 'textarea',
			'label' => __( 'Alert Text', 'junkie' ),
			'desc'  => __( 'Add the alert\'s text', 'junkie' ),
		)

	)
);

/* Toggle Config --- */

$junkie_shortcodes['toggle'] = array(
	'title'    => __( 'Toggle', 'junkie' ),
	'id'       => 'junkie-toggle-shortcode',
	'template' => ' {{child_shortcode}} ', // There is no wrapper shortcode
	'notes'    => __( 'Click \'Add Toggle\' to add a new toggle. Drag and drop to reorder toggles.', 'junkie' ),
	'params'   => array(),
	'child_shortcode' => array(
		'params' => array(
			'title' => array(
				'type'  => 'text',
				'label' => __( 'Toggle Content Title', 'junkie' ),
				'desc'  => __( 'Add the title that will go above the toggle content', 'junkie' ),
				'std'  => __( 'Title', 'junkie' )
			),
			'content' => array(
				'std'   => __( 'Content', 'junkie' ),
				'type'  => 'textarea',
				'label' => __( 'Toggle Content', 'junkie' ),
				'desc'  => __( 'Add the toggle content. Will accept HTML', 'junkie' ),
			),
			'state' => array(
				'type'    => 'select',
				'label'   => __( 'Toggle State', 'junkie' ),
				'desc'    => __( 'Select the state of the toggle on page load', 'junkie' ),
				'options' => array(
					'open'   => __( 'Open', 'junkie' ),
					'closed' => __( 'Closed', 'junkie' )
				)
			)
		),
		'template'     => '[junkie-toggle {{attributes}}] {{content}} [/junkie-toggle]',
		'clone_button' => __( 'Add Toggle', 'junkie' )
	)
);

/* Tabs Config --- */

$junkie_shortcodes['tabs'] = array(
    'title'    => __( 'Tab', 'junkie' ),
    'id'       => 'junkie-tabs-shortcode',
    'template' => '[junkie-tabs] {{child_shortcode}} [/junkie-tabs]',
    'notes'    => __('Click \'Add Tag\' to add a new tag. Drag and drop to reorder tabs.', 'junkie' ),
    'params'   => array(),
    'child_shortcode' => array(
        'params' => array(
            'title' => array(
                'std'   => __( 'Title', 'junkie' ),
                'type'  => 'text',
                'label' => __( 'Tab Title', 'junkie' ),
                'desc'  => __( 'Title of the tab.', 'junkie' ),
            ),
            'content' => array(
                'std'   => __( 'Tab Content', 'junkie' ),
                'type'  => 'textarea',
                'label' => __( 'Tab Content', 'junkie' ),
                'desc'  => __( 'Add the tabs content.', 'junkie' )
            )
        ),
        'template'     => '[junkie-tab {{attributes}}] {{content}} [/junkie-tab]',
        'clone_button' => __( 'Add Tab', 'junkie' )
    )
);

/* Columns Config --- */

$junkie_shortcodes['columns'] = array(
	'title'    => __( 'Columns', 'junkie' ),
	'id'       => 'junkie-columns-shortcode',
	'template' => ' {{child_shortcode}} ', // There is no wrapper shortcode
	'notes'    => __( 'Click \'Add Column\' to add a new column. Drag and drop to reorder columns.', 'junkie' ),
	'params'   => array(),
	'child_shortcode' => array(
		'params' => array(
			'column' => array(
				'type'    => 'select',
				'label'   => __( 'Column Type', 'junkie' ),
				'desc'    => __( 'Select the width of the column.', 'junkie' ),
				'options' => array(
					'one-third'    => __( 'One Third', 'junkie' ),
					'two-third'    => __( 'Two Thirds', 'junkie' ),
					'one-half'     => __( 'One Half', 'junkie' ),
					'one-fourth'   => __( 'One Fourth', 'junkie' ),
					'three-fourth' => __( 'Three Fourth', 'junkie' ),
					'one-fifth'    => __( 'One Fifth', 'junkie' ),
					'two-fifth'    => __( 'Two Fifth', 'junkie' ),
					'three-fifth'  => __( 'Three Fifth', 'junkie' ),
					'four-fifth'   => __( 'Four Fifth', 'junkie' ),
					'one-sixth'    => __( 'One Sixth', 'junkie' ),
					'five-sixth'   => __( 'Five Sixth', 'junkie' )
				)
			),
			'last' => array(
				'type'    => 'checkbox',
				'label'   => __( 'Last column', 'junkie' ),
				'desc'    => __( 'Set whether this is the last column.', 'junkie' ),
				'default' => false
			),
			'content' => array(
				'std'   => __( 'Column content', 'junkie' ),
				'type'  => 'textarea',
				'label' => __( 'Column Content', 'junkie' ),
				'desc'  => __( 'Add the column content.', 'junkie' )
			)
		),
		'template'     => '[junkie-column {{attributes}}] {{content}} [/junkie-column]',
		'clone_button' => __( 'Add Column', 'junkie' )
	)
);