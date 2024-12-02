<?php
namespace UP\PresetsGenerator\Core;

use UP\PresetsGenerator\Presets\Typography;
use UP\PresetsGenerator\Presets\Colors;

class Loader {
    private $typography;
    private $colors;

    public function __construct() {
        error_log('UP Presets Generator: Loader construit');
        add_action('admin_menu', [$this, 'add_admin_menu'], 9);
        add_action('admin_menu', [$this, 'init_presets'], 11);
    }

    public function add_admin_menu(): void {
        error_log('UP Presets Generator: Ajout du menu principal');
        add_menu_page(
            'UP Presets Generator',
            'UP Presets',
            'manage_options',
            'up-presets-generator',
            [$this, 'render_main_page'],
            'dashicons-admin-appearance',
            30
        );

        add_submenu_page(
            'up-presets-generator',
            'UP Presets Generator',
            'Vue d\'ensemble',
            'manage_options',
            'up-presets-generator',
            [$this, 'render_main_page']
        );
    }

    public function init_presets(): void {
        error_log('UP Presets Generator: Initialisation des presets');
        $this->typography = new Typography();
        $this->colors = new Colors();
    }

    public function render_main_page(): void {
        ?>
        <div class="wrap">
            <h1>UP Presets Generator</h1>
            <div class="welcome-panel">
                <div class="welcome-panel-content">
                    <h2>Bienvenue dans UP Presets Generator</h2>
                    <p class="about-description">
                        Générez et gérez vos presets pour Gutenberg facilement.
                    </p>
                    <div class="welcome-panel-column-container">
                        <div class="welcome-panel-column">
                            <h3>Commencez avec :</h3>
                            <ul>
                                <li>
                                    <a href="<?php echo admin_url('admin.php?page=up-preset-typography'); ?>" class="button button-primary">
                                        Gérer les presets de typographie
                                    </a>
                                </li>
                                <li style="margin-top: 10px;">
                                    <a href="<?php echo admin_url('admin.php?page=up-preset-colors'); ?>" class="button button-primary">
                                        Gérer les presets de couleurs
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
} 