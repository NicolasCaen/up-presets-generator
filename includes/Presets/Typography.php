<?php
namespace UP\PresetsGenerator\Presets;

use UP\PresetsGenerator\Core\AbstractPreset;

class Typography extends AbstractPreset {
    public function __construct() {
        parent::__construct(
            'typography',
            'Presets Typographie',
            'Typographie'
        );
    }

    public function register_settings(): void {
        register_setting(
            'up_preset_typography',
            'up_presets_typography',
            [$this, 'validate_data']
        );
    }

    public function render_form(): void {
        $preset_data = isset($_GET['edit']) ? $this->get_preset_data($_GET['edit']) : [];
        ?>
        <div class="up-preset-form">
            <p>
                <label for="preset_name">Nom du preset :</label>
                <input type="text" id="preset_name" name="preset[name]" 
                       value="<?php echo esc_attr($preset_data['name'] ?? ''); ?>" required>
            </p>
            
            <div class="typography-group">
                <h3>Paramètres de typographie</h3>
                <div class="font-inputs">
                    <p>
                        <label>Police principale :</label>
                        <input type="text" name="preset[typography][fontFamily]" 
                               value="<?php echo esc_attr($preset_data['typography']['fontFamily'] ?? ''); ?>">
                    </p>
                    <p>
                        <label>Taille de base :</label>
                        <input type="number" name="preset[typography][fontSize]" 
                               value="<?php echo esc_attr($preset_data['typography']['fontSize'] ?? '16'); ?>">
                    </p>
                </div>
            </div>
        </div>
        <?php
    }

    public function validate_data(array $data): array {
        if (empty($data['name'])) {
            add_settings_error(
                'up_presets_typography',
                'missing_name',
                'Le nom du preset est requis'
            );
            return [];
        }
        return $data;
    }

    public function generate_json(array $data): array {
        return [
            'version' => 2,
            'settings' => [
                'typography' => [
                    'fontFamilies' => [
                        [
                            'fontFamily' => $data['typography']['fontFamily'],
                            'name' => 'Principal',
                            'slug' => 'primary'
                        ]
                    ],
                    'fontSize' => $data['typography']['fontSize']
                ]
            ]
        ];
    }

    private function get_preset_data(string $preset_id): array {
        $presets = get_option('up_presets_typography', []);
        return $presets[$preset_id] ?? [];
    }
} 