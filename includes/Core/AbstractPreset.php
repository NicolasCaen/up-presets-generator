<?php
namespace UP\PresetsGenerator\Core;

abstract class AbstractPreset {
    protected $preset_type;
    protected $page_title;
    protected $menu_title;
    
    public function __construct($preset_type, $page_title, $menu_title) {
        $this->preset_type = $preset_type;
        $this->page_title = $page_title;
        $this->menu_title = $menu_title;
        
        add_action('admin_menu', [$this, 'add_admin_submenu'], 20);
        add_action('admin_init', [$this, 'register_settings']);
    }
    
    abstract public function render_form(): void;
    abstract public function validate_data(array $data): array;
    abstract public function generate_json(array $data): array;
    abstract public function register_settings(): void;
    
    public function add_admin_submenu(): void {
        error_log('Ajout du sous-menu pour ' . $this->preset_type);
        add_submenu_page(
            'up-presets-generator',
            $this->page_title,
            $this->menu_title,
            'manage_options',
            'up-presets-' . $this->preset_type,
            [$this, 'render_admin_page']
        );
    }
    
    public function render_admin_page(): void {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html($this->page_title); ?></h1>
            
            <form method="post" action="options.php">
                <?php
                settings_fields('up_preset_' . $this->preset_type);
                do_settings_sections('up-preset-' . $this->preset_type);
                $this->render_form();
                submit_button();
                ?>
            </form>
            
            <?php $this->render_presets_table(); ?>
        </div>
        <?php
    }
    
    protected function render_presets_table(): void {
        $presets = get_option('up_presets_' . $this->preset_type, []);
        if (empty($presets)) {
            return;
        }
        ?>
        <h2>Presets existants</h2>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($presets as $key => $preset) : ?>
                <tr>
                    <td><?php echo esc_html($preset['name']); ?></td>
                    <td>
                        <button class="button" data-preset="<?php echo esc_attr(json_encode($preset)); ?>">
                            Copier vers le thème
                        </button>
                        <button class="button" data-edit="<?php echo esc_attr($key); ?>">
                            Modifier
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php
    }
} 