<?php

/**
 * Editor Menu Management Customizations
 *
 * - Grants editors the capability to manage menus.
 * - Restricts editors to only edit the Primary Menu (ID = 3).
 * - Hides menu location settings and "Add Menu" button for editors.
 * - Prevents editors from deleting menus or changing menu locations.
 *
 * @package NLSA_Theme
 * @since 1.0.0
 */

// Allow editors to manage menus
function nlsa_allow_editors_manage_menus() {
    $role = get_role('editor');

    if ($role && !$role->has_cap('edit_theme_options')) {
        $role->add_cap('edit_theme_options');
    }
}
add_action('init', 'nlsa_allow_editors_manage_menus');


// Lock editors to only edit the Primary Menu (ID = 3)
function nlsa_lock_editor_to_primary_menu() {
    if (!current_user_can('editor') || current_user_can('administrator')) {
        return;
    }

    $allowed_menu_id = 3;
    ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const menuSelect = document.querySelector('#menu');

            

            if (menuSelect) {
                menuSelect.value = "<?php echo $allowed_menu_id; ?>";
                menuSelect.style.pointerEvents = 'none';
                menuSelect.style.backgroundColor = '#eee';
            }

            const form = document.querySelector('#update-nav-menu');

            if (form) {
                form.addEventListener('submit', function () {
                    let menuInput = document.querySelector('input[name="menu"]');

                    if (!menuInput) {
                        menuInput = document.createElement('input');
                        menuInput.type = 'hidden';
                        menuInput.name = 'menu';
                        form.appendChild(menuInput);
                    }

                    menuInput.value = "<?php echo $allowed_menu_id; ?>";
                });
            }
        });
    </script>
    <?php
}
add_action('admin_footer-nav-menus.php', 'nlsa_lock_editor_to_primary_menu');


// Prevent editors from deleting menus
function nlsa_prevent_menu_deletion($menu_id) {
    if (current_user_can('editor') && !current_user_can('administrator')) {
        wp_die('You are not allowed to delete menus.');
    }
}
add_action('wp_delete_nav_menu', 'nlsa_prevent_menu_deletion');

// Clean up the menu editor UI for editors (hide delete button, switching menus, and settings)
function nlsa_clean_menu_ui_for_editors() {
    if (!current_user_can('editor') || current_user_can('administrator')) {
        return;
    }

    echo '<style>

        /* ===============================
          CUSTOM MESSAGE (NO FLICKER)
        =============================== */

        .manage-menus .add-edit-menu-action {
            display: none !important;
        }

        .manage-menus {
            border-left: 4px solid #0073aa;
            padding-left: 12px;
            margin-bottom: 10px;
        }

        .manage-menus::before {
            content: "Edit your menu below and do not forget to save your changes!";
            display: block;
            font-weight: 500;
        }

        /* ===============================
          HIDE MENU SETTINGS
        =============================== */
        .menu-settings {
            display: none !important;
        }

        /* ===============================
          HIDE MANAGE LOCATIONS
        =============================== */
        #nav-menu-theme-locations,
        .nav-menu-locations,
        .menu-locations {
            display: none !important;
        }

        /* ===============================
          HIDE DELETE MENU (ONLY MENU, NOT ITEMS)
        =============================== */
        #delete-action,
        #delete-menu-action,
        .delete-action {
            display: none !important;
        }

        /* ===============================
          HIDE MENU SWITCHING TABS
        =============================== */
        .nav-tab-wrapper a:not(.nav-tab-active) {
            display: none !important;
        }

        /* ===============================
          DISABLE MENU NAME EDITING
        =============================== */
        #menu-name {
            pointer-events: none !important;
            background: #f1f1f1 !important;
            color: #666 !important;
        }

        label[for="menu-name"] {
            opacity: 0.5;
        }

    </style>';
}
add_action('admin_head-nav-menus.php', 'nlsa_clean_menu_ui_for_editors');

// Force editors to always edit the Primary Menu (ID = 3) on the backend
function nlsa_force_primary_menu_backend($menu_id) {
    if (current_user_can('editor') && !current_user_can('administrator')) {
        return 3; // always force Primary Menu
    }
    return $menu_id;
}

add_filter('wp_edit_nav_menu_walker', function($walker, $menu_id) {
    if (current_user_can('editor') && !current_user_can('administrator')) {
        return $walker;
    }
    return $walker;
}, 10, 2);

// Hide "Add Menu" button for editors to prevent creating new menus
function nlsa_hide_add_menu_button() {
    if (current_user_can('editor') && !current_user_can('administrator')) {
        echo '<style>
            .page-title-action { display: none !important; }
        </style>';
    }
}
add_action('admin_head-nav-menus.php', 'nlsa_hide_add_menu_button');


function nlsa_disable_menu_name_for_editors( $args ) {
    if ( current_user_can('editor') && ! current_user_can('administrator') ) {

        // Remove menu name field entirely
        add_filter('wp_nav_menu_manage_columns', '__return_empty_array');

        // Disable menu name input rendering
        add_action('admin_footer-nav-menus.php', function () {
            echo '<style>
                #menu-name {
                    pointer-events: none;
                    background: #f1f1f1;
                }

                label[for="menu-name"] {
                    opacity: 0.6;
                }
            </style>';
        });
    }

    return $args;
}

// Block saving menu location changes for editors
function nlsa_lock_menu_locations_save($value) {

    if (current_user_can('editor') && !current_user_can('administrator')) {
        return get_theme_mod('nav_menu_locations'); // keep existing
    }

    return $value;
}
add_filter('pre_set_theme_mod_nav_menu_locations', 'nlsa_lock_menu_locations_save');

// Hide menu location settings from editors in the Customizer
function nlsa_disable_menu_locations_customizer($wp_customize) {
    if (current_user_can('editor') && !current_user_can('administrator')) {

        // Remove the whole section
        $wp_customize->remove_section('menu_locations');
    }
}
add_action('customize_register', 'nlsa_disable_menu_locations_customizer', 100);

// Additional CSS fallback to hide menu location settings in case the section is still rendered
function nlsa_hide_menu_locations_customizer_ui() {
    if (current_user_can('editor') && !current_user_can('administrator')) {
        echo '<style>
            #accordion-section-menu_locations {
                display: none !important;
            }
        </style>';
    }
}
add_action('customize_controls_print_styles', 'nlsa_hide_menu_locations_customizer_ui');

// Hide "Add Menu" button in Customizer for editors
function nlsa_hide_create_menu_button_customizer() {
    if (current_user_can('editor') && !current_user_can('administrator')) {
        echo '<style>
            /* Hide "Create New Menu" button */
            .customize-control-create-nav-menu {
                display: none !important;
            }

            /* Hide "Create New Menu" inside panel (fallback) */
            .customize-pane-child .customize-control-create-nav-menu {
                display: none !important;
            }

            /* Extra fallback for newer WP markup */
            button.create-menu,
            .button.create-menu {
                display: none !important;
            }
        </style>';
    }
}
add_action('customize_controls_print_styles', 'nlsa_hide_create_menu_button_customizer');

// Prevent editors from creating new menus via the backend
function nlsa_prevent_editor_menu_creation($term, $taxonomy) {
    if ($taxonomy === 'nav_menu' && current_user_can('editor') && !current_user_can('administrator')) {
        wp_die('You are not allowed to create menus.');
    }
}
add_action('created_term', 'nlsa_prevent_editor_menu_creation', 10, 2);

// Additional JavaScript fallback to remove "Create New Menu" button in Customizer for editors (handles dynamic rendering)
function nlsa_remove_create_menu_button_customizer_js() {
    if (current_user_can('editor') && !current_user_can('administrator')) {
        ?>
        <script>
        (function() {

            function removeCreateMenu() {
                document.querySelectorAll('button, a').forEach(el => {
                    if (el.textContent.trim() === 'Create New Menu') {
                        el.remove();
                    }
                });
            }

            // Run repeatedly for a short time (handles async rendering)
            let attempts = 0;
            const interval = setInterval(() => {
                removeCreateMenu();
                attempts++;

                if (attempts > 20) {
                    clearInterval(interval);
                }
            }, 300);

            // Also observe future DOM changes
            const observer = new MutationObserver(removeCreateMenu);
            observer.observe(document.body, {
                childList: true,
                subtree: true
            });

        })();
        </script>
        <?php
    }
}
add_action('customize_controls_print_footer_scripts', 'nlsa_remove_create_menu_button_customizer_js');

// Hide menu location settings and "Automatically add new pages" option inside the menu editor for editors in the Customizer
function nlsa_hide_menu_controls_inside_menu_customizer() {
    if (current_user_can('editor') && !current_user_can('administrator')) {
        echo '<style>

            /* Menu location */
            .menu-location-settings,
            .customize-control-nav_menu_locations {
                display: none !important;
            }

            /* Auto-add pages */
            .customize-control-nav_menu_auto_add,
            .nav-menu-auto-add,
            .auto-add-pages {
                display: none !important;
            }

            /* DELETE MENU (REAL FIX) */
            .customize-control-nav_menu_delete,
            .menu-delete,
            .menu-delete-item,
            .delete-menu,
            button[name="remove-menu"],
            button[data-action="remove-menu"],
            .customize-section .menu-delete {
                display: none !important;
            }

        </style>';
    }
}
add_action('customize_controls_print_styles', 'nlsa_hide_menu_controls_inside_menu_customizer');

// Prevent editors from changing the "Automatically add new pages" setting
function nlsa_lock_auto_add_pages($value) {
    if (current_user_can('editor') && !current_user_can('administrator')) {
        return get_theme_mod('nav_menu_options'); // keep existing
    }
    return $value;
}
add_filter('pre_set_theme_mod_nav_menu_options', 'nlsa_lock_auto_add_pages');
