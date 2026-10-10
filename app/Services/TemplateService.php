<?php

namespace App\Services;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class TemplateService
{
    public const TEMPLATE_CANVAS = 'canvas';
    public const TEMPLATE_CUSTOM_ORG = 'custom_org';
    public const TEMPLATE_MODERN_ORG = 'modern_org';

    /**
     * Get all available templates metadata
     */
    public static function getAvailableTemplates(): array
    {
        return [
            self::TEMPLATE_CANVAS => [
                'id' => self::TEMPLATE_CANVAS,
                'number' => 1,
                'name' => 'Template 1 — Canvas',
                'short_name' => 'Canvas',
                'badge' => 'Default / Existing',
                'badge_class' => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 border-slate-300',
                'color' => '#10b981',
                'thumbnail' => asset('assets/frontend/templates/thumbnails/canvas.svg'),
                'subtitle' => 'Classic Multipurpose & Comprehensive',
                'description' => 'Template default existing organisasi ASKLIN dengan tata letak klasik multipurpose, megamenu komprehensif, slider berputar, dan struktur layout bawaan Canvas.',
                'features' => [
                    'Tata letak multi-purpose klasik Canvas',
                    'Dukungan penuh semua halaman bawaan ASKLIN',
                    'Megamenu navigasi komprehensif',
                    'Integrasi slider dan galeri bawaan',
                ],
                'tech_stack' => 'Canvas HTML5, Bootstrap Grid, OwlCarousel',
            ],
            self::TEMPLATE_CUSTOM_ORG => [
                'id' => self::TEMPLATE_CUSTOM_ORG,
                'number' => 2,
                'name' => 'Template 2 — Custom Organization',
                'short_name' => 'Custom Organization',
                'badge' => 'Formal & Institutional',
                'badge_class' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/70 dark:text-emerald-300 border-emerald-300',
                'color' => '#047857',
                'thumbnail' => asset('assets/frontend/templates/thumbnails/custom_org.svg'),
                'subtitle' => 'Formal, Clean & Institutional Healthcare',
                'description' => 'Dirancang dan dibangun dari nol khusus organisasi profesi kesehatan ASKLIN. Mengutamakan pendekatan resmi, formal, bersih, kredibel, dan berwibawa dengan tata letak institusional yang teratur, hierarki informasi jelas, dan kemudahan menemukan profil & layanan klinik.',
                'features' => [
                    'Desain formal institusional berstandar asosiasi kesehatan nasional',
                    'Palet warna Forest Emerald & Deep Medical Slate yang berwibawa',
                    'Kartu profil organisasi, susunan pengurus, dan hierarki berita yang teratur',
                    'Top-bar informasi kontak kantor resmi & akses cepat ke portal anggota',
                    'Tipografi Plus Jakarta Sans dengan keterbacaan tinggi dan responsif',
                ],
                'tech_stack' => 'Custom CSS Grid/Flexbox, Pure Modern CSS, Isolated Scope',
            ],
            self::TEMPLATE_MODERN_ORG => [
                'id' => self::TEMPLATE_MODERN_ORG,
                'number' => 3,
                'name' => 'Template 3 — Modern Organization',
                'short_name' => 'Modern Organization',
                'badge' => 'Modern & Dynamic',
                'badge_class' => 'bg-teal-100 text-teal-800 dark:bg-teal-950/70 dark:text-teal-300 border-teal-300',
                'color' => '#0d9488',
                'thumbnail' => asset('assets/frontend/templates/thumbnails/modern_org.svg'),
                'subtitle' => 'Modern, Dynamic & Premium Healthcare Association',
                'description' => 'Dirancang dari nol dengan konsep modern kontemporer yang elegan dan dinamis. Menampilkan visual hero yang memukau, header dark-accent berkelas, floating badge metrik statistik, sentuhan visual glassmorphic halus, dan pengalaman interaksi yang memikat tanpa mengurangi marwah asosiasi resmi.',
                'features' => [
                    'Konsep modern kontemporer dengan dynamic dark header & glowing accents',
                    'Hero split layout dengan live metric badges & quick-action CTA keanggotaan',
                    'Komponen visual berita dan agenda bergaya modern card & pill tags',
                    'Aksen visual Teal, Emerald, dan Cyan yang segar dan berkelas',
                    'Micro-interactions halus dan pengalaman membaca yang interaktif di semua perangkat',
                ],
                'tech_stack' => 'Modern CSS Glassmorphism, CSS Custom Properties, Isolated Scope',
            ],
        ];
    }

    /**
     * Get active template identifier (from setting or preview session)
     */
    public static function getActiveTemplate(): string
    {
        if (self::isPreviewMode()) {
            return self::getPreviewTemplate();
        }

        $active = setting('frontend_template', self::TEMPLATE_CANVAS);
        $templates = self::getAvailableTemplates();

        return array_key_exists($active, $templates) ? $active : self::TEMPLATE_CANVAS;
    }

    /**
     * Get configured persistent active template (ignoring preview)
     */
    public static function getConfiguredTemplate(): string
    {
        $active = setting('frontend_template', self::TEMPLATE_CANVAS);
        $templates = self::getAvailableTemplates();

        return array_key_exists($active, $templates) ? $active : self::TEMPLATE_CANVAS;
    }

    /**
     * Get active template metadata array
     */
    public static function getActiveTemplateData(): array
    {
        $key = self::getActiveTemplate();
        $templates = self::getAvailableTemplates();

        return $templates[$key] ?? $templates[self::TEMPLATE_CANVAS];
    }

    /**
     * Determine if current request is in preview mode
     */
    public static function isPreviewMode(): bool
    {
        if (!Auth::check()) {
            return false;
        }

        $user = Auth::user();
        $canPreview = $user->hasRole(['Superadmin', 'Admin Pusat', 'Admin Cabang', 'Admin Daerah']) || $user->can('secret');

        if (!$canPreview) {
            return false;
        }

        // Query param override
        $requested = request()->query('preview_template');
        if ($requested && array_key_exists($requested, self::getAvailableTemplates())) {
            session(['preview_frontend_template' => $requested]);
            return true;
        }

        // Session check
        if (session()->has('preview_frontend_template')) {
            $sessTemplate = session('preview_frontend_template');
            return array_key_exists($sessTemplate, self::getAvailableTemplates());
        }

        return false;
    }

    /**
     * Get preview template key
     */
    public static function getPreviewTemplate(): string
    {
        $requested = request()->query('preview_template');
        if ($requested && array_key_exists($requested, self::getAvailableTemplates())) {
            return $requested;
        }

        return session('preview_frontend_template', self::TEMPLATE_CANVAS);
    }

    /**
     * Set preview mode in session
     */
    public static function setPreview(string $templateKey): bool
    {
        $templates = self::getAvailableTemplates();
        if (!array_key_exists($templateKey, $templates)) {
            return false;
        }

        session(['preview_frontend_template' => $templateKey]);
        return true;
    }

    /**
     * Clear preview session
     */
    public static function clearPreview(): void
    {
        session()->forget('preview_frontend_template');
    }

    /**
     * Set active template in persistent settings
     */
    public static function setActiveTemplate(string $templateKey): bool
    {
        $templates = self::getAvailableTemplates();
        if (!array_key_exists($templateKey, $templates)) {
            return false;
        }

        setting()->set('frontend_template', $templateKey);
        self::clearPreview();

        return true;
    }

    /**
     * Resolve and return view based on active template
     *
     * @param string $viewKey (e.g. 'index', 'sejarah', 'visimisi', or 'frontend.home.index')
     * @param array $data
     * @param array $mergeData
     * @return \Illuminate\Contracts\View\View
     */
    public static function view(string $viewKey, array $data = [], array $mergeData = [])
    {
        $cleanedKey = str_replace(['frontend.home.', 'frontend.', 'auth.'], '', $viewKey);
        $activeTemplate = self::getActiveTemplate();

        if ($activeTemplate === self::TEMPLATE_CUSTOM_ORG) {
            $customView = "frontend.templates.custom_org.{$cleanedKey}";
            if (View::exists($customView)) {
                return view($customView, $data, $mergeData);
            }
        } elseif ($activeTemplate === self::TEMPLATE_MODERN_ORG) {
            $modernView = "frontend.templates.modern_org.{$cleanedKey}";
            if (View::exists($modernView)) {
                return view($modernView, $data, $mergeData);
            }
        }

        // Default Canvas fallback
        $canvasView = "frontend.home.{$cleanedKey}";
        if (View::exists($canvasView)) {
            return view($canvasView, $data, $mergeData);
        }

        $frontendView = "frontend.{$cleanedKey}";
        if (View::exists($frontendView)) {
            return view($frontendView, $data, $mergeData);
        }

        $authView = "auth.{$cleanedKey}";
        if (View::exists($authView)) {
            return view($authView, $data, $mergeData);
        }

        return view($viewKey, $data, $mergeData);
    }
}
