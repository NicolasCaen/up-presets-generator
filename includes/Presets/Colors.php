<?php
namespace UP\PresetsGenerator\Presets;

use UP\PresetsGenerator\Core\AbstractPreset;

class Colors extends AbstractPreset {
    public function __construct() {
        parent::__construct(
            'colors',
            'Presets Couleurs',
            'Couleurs'
        );
    }

    public function register_settings(): void {
        register_setting(
            'up_preset_colors',
            'up_presets_colors',
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
            
            <div class="colors-group">
                <h3>Couleurs du thème</h3>
                <div class="color-inputs">
                    <p>
                        <label>Couleur principale :</label>
                        <input type="color" name="preset[colors][primary]" 
                               value="<?php echo esc_attr($preset_data['colors']['primary'] ?? '#000000'); ?>">
                    </p>
                    <p>
                        <label>Couleur secondaire :</label>
                        <input type="color" name="preset[colors][secondary]" 
                               value="<?php echo esc_attr($preset_data['colors']['secondary'] ?? '#000000'); ?>">
                    </p>
                    <p>
                        <label>Couleur de fond :</label>
                        <input type="color" name="preset[colors][background]" 
                               value="<?php echo esc_attr($preset_data['colors']['background'] ?? '#ffffff'); ?>">
                    </p>
                    <p>
                        <label>Couleur du texte :</label>
                        <input type="color" name="preset[colors][text]" 
                               value="<?php echo esc_attr($preset_data['colors']['text'] ?? '#000000'); ?>">
                    </p>
                </div>
            </div>
        </div>
        <?php
    }

    public function validate_data(array $data): array {
        if (empty($data['name'])) {
            add_settings_error(
                'up_presets_colors',
                'missing_name',
                'Le nom du preset est requis'
            );
            return [];
        }

        // Validation des couleurs
        $required_colors = ['primary', 'secondary', 'background', 'text'];
        foreach ($required_colors as $color) {
            if (empty($data['colors'][$color])) {
                add_settings_error(
                    'up_presets_colors',
                    'missing_color',
                    sprintf('La couleur %s est requise', $color)
                );
                return [];
            }
        }

        return $data;
    }

    public function generate_json(array $data): array {
        return [
            'version' => 2,
            'settings' => [
                'color' => [
                    'palette' => [
                        [
                            'name' => 'Principal',
                            'slug' => 'primary',
                            'color' => $data['colors']['primary']
                        ],
                        [
                            'name' => 'Secondaire',
                            'slug' => 'secondary',
                            'color' => $data['colors']['secondary']
                        ],
                        [
                            'name' => 'Arrière-plan',
                            'slug' => 'background',
                            'color' => $data['colors']['background']
                        ],
                        [
                            'name' => 'Texte',
                            'slug' => 'text',
                            'color' => $data['colors']['text']
                        ]
                    ]
                ]
            ]
        ];
    }

    private function get_preset_data(string $preset_id): array {
        $presets = get_option('up_presets_colors', []);
        return $presets[$preset_id] ?? [];
    }
} 