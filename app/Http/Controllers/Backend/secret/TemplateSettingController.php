<?php

namespace App\Http\Controllers\Backend\secret;

use App\Http\Controllers\Controller;
use App\Services\TemplateService;
use Illuminate\Http\Request;

class TemplateSettingController extends Controller
{
    public function index()
    {
        $title = "Pengaturan Template Frontend";
        $templates = TemplateService::getAvailableTemplates();
        $activeTemplate = TemplateService::getConfiguredTemplate();
        $isPreview = TemplateService::isPreviewMode();
        $previewTemplate = TemplateService::getPreviewTemplate();

        return view('backend.secret.template_settings.index', compact(
            'title',
            'templates',
            'activeTemplate',
            'isPreview',
            'previewTemplate'
        ));
    }

    public function update(Request $request)
    {
        $availableKeys = implode(',', array_keys(TemplateService::getAvailableTemplates()));

        $request->validate([
            'template' => "required|string|in:{$availableKeys}",
        ]);

        $success = TemplateService::setActiveTemplate($request->template);

        if (!$success) {
            return redirect()->back()->with('error', 'Gagal mengubah template. Template tidak valid.');
        }

        $templates = TemplateService::getAvailableTemplates();
        $chosenName = $templates[$request->template]['name'] ?? $request->template;

        return redirect()->route('template_settings.index')->with(
            'success',
            "Template frontend aktif berhasil diubah menjadi: {$chosenName}"
        );
    }

    public function preview($template)
    {
        $templates = TemplateService::getAvailableTemplates();

        if (!array_key_exists($template, $templates)) {
            return redirect()->back()->with('error', 'Template pratinjau tidak valid.');
        }

        TemplateService::setPreview($template);

        return redirect()->route('home', ['preview_template' => $template]);
    }

    public function exitPreview()
    {
        TemplateService::clearPreview();

        return redirect()->route('template_settings.index')->with(
            'success',
            'Mode pratinjau dinonaktifkan. Anda kembali ke konfigurasi aktif.'
        );
    }
}
