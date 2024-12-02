<?php
/**
 * Plugin Name: UP Presets Generator
 * Description: Générateur de presets JSON pour Gutenberg
 * Version: 1.0.0
 * Author: GEHIN Nicolas
 */

namespace UP\PresetsGenerator;

if (!defined('ABSPATH')) {
    exit;
}

// Définir la constante pour le chemin du plugin
define('UP_PRESETS_GENERATOR_PATH', plugin_dir_path(__FILE__));
define('UP_PRESETS_GENERATOR_URL', plugin_dir_url(__FILE__));

// Autoloader pour les classes
spl_autoload_register(function ($class) {
    // Vérifie si la classe appartient à notre namespace
    if (strpos($class, 'UP\PresetsGenerator\\') !== 0) {
        return;
    }

    // Convertit le namespace en chemin de fichier
    $relative_class = substr($class, strlen('UP\PresetsGenerator\\'));
    $file = UP_PRESETS_GENERATOR_PATH . 'includes/' . str_replace('\\', '/', $relative_class) . '.php';
    
    if (file_exists($file)) {
        require_once $file;
    }
});

// Initialisation du plugin
if (!function_exists('UP\PresetsGenerator\init_plugin')) {
    function init_plugin() {
        static $instance = null;
        
        if ($instance === null) {
            $instance = new Core\Loader();
        }
        
        return $instance;
    }
}

// Hook pour l'initialisation
add_action('init', 'UP\PresetsGenerator\init_plugin');
