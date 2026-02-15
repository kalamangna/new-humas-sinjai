<?php

namespace App\Controllers\Frontend;

use App\Models\ProfileModel;

class Page extends BaseController
{
    public function about()
    {
        $data['seo'] = $this->seoData;
        $data['seo']['title'] = 'Tentang Kami';
        return view('frontend/pages/about', $data);
    }

    public function contact()
    {
        $data['seo'] = $this->seoData;
        $data['seo']['title'] = 'Hubungi Kami';
        return view('frontend/pages/contact', $data);
    }

    public function widget()
    {
        $data['seo'] = $this->seoData;
        $data['seo']['title'] = 'Panduan Widget';
        return view('frontend/pages/widget_guide', $data);
    }

    public function profile($type)
    {
        $model = new ProfileModel();
        
        // Handle "pejabat-daerah" alias for "forkopimda"
        $originalType = $type;
        if ($type === 'pejabat-daerah') {
            $type = 'forkopimda';
        }

        // Check if it's a valid type
        $validTypes = ['bupati', 'wakil-bupati', 'sekda', 'forkopimda', 'eselon-ii', 'eselon-iii', 'eselon-iv', 'kepala-desa'];
        
        if (!in_array($type, $validTypes)) {
             // Fallback: check if it's a slug for a specific profile
             $profile = $model->where('slug', $originalType)->first();
             if ($profile) {
                 $data['profile'] = $profile;
                 $data['seo'] = $this->seoData;
                 $data['seo']['title'] = $profile['name'] ?: ($profile['position'] ?: 'Profil');
                 $data['seo']['description'] = limit_char($profile['bio'] ?? '', 160);
                 if (!empty($profile['image'])) {
                     $data['seo']['image'] = base_url($profile['image']);
                 }
                 return view('frontend/profiles/detail', $data);
             }
             throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $title = $type === 'forkopimda' ? 'Pejabat Daerah' : ucwords(str_replace('-', ' ', $type));
        $data['type'] = $type;

        $data['seo'] = $this->seoData;
        $data['seo']['title'] = $title;

        if ($type == 'forkopimda') {
            $officials = $model->whereIn('type', ['forkopimda', 'eselon-ii', 'eselon-iii', 'eselon-iv', 'kepala-desa'])
                               ->orderBy('type', 'ASC')
                               ->orderBy('order', 'ASC')
                               ->findAll();
            
            $data['groupedProfiles'] = [
                'Forkopimda' => [],
                'Eselon II' => [],
                'Eselon III' => [],
                'Eselon IV' => [],
                'Kepala Desa' => []
            ];

            foreach ($officials as $official) {
                if ($official['type'] == 'forkopimda') $data['groupedProfiles']['Forkopimda'][] = $official;
                elseif ($official['type'] == 'eselon-ii') $data['groupedProfiles']['Eselon II'][] = $official;
                elseif ($official['type'] == 'eselon-iii') $data['groupedProfiles']['Eselon III'][] = $official;
                elseif ($official['type'] == 'eselon-iv') $data['groupedProfiles']['Eselon IV'][] = $official;
                elseif ($official['type'] == 'kepala-desa') $data['groupedProfiles']['Kepala Desa'][] = $official;
            }
            
            return view('frontend/profiles/index', $data);
        } else {
            $data['profile'] = $model->where('type', $type)->orderBy('order', 'ASC')->orderBy('created_at', 'DESC')->first();
            if ($data['profile']) {
                $data['seo']['description'] = limit_char($data['profile']['bio'] ?? '', 160);
                if (!empty($data['profile']['image'])) {
                    $data['seo']['image'] = base_url($data['profile']['image']);
                }
            }
            return view('frontend/profiles/detail', $data);
        }
    }
}