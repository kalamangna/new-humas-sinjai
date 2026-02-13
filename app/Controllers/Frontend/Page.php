<?php

namespace App\Controllers\Frontend;

use App\Models\ProfileModel;

class Page extends BaseController
{
    public function about()
    {
        $data['title'] = 'Tentang';
        return view('Frontend/about', $data);
    }

    public function contact()
    {
        $data['title'] = 'Kontak';
        return view('Frontend/contact', $data);
    }

    public function widget()
    {
        $data['title'] = 'Panduan Widget';
        return view('Frontend/widget_guide', $data);
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
                 $data['title'] = $profile['name'] ?: ($profile['position'] ?: 'Profil');
                 $data['profile'] = $profile;
                 return view('Frontend/profile_detail', $data);
             }
             throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data['title'] = $type === 'forkopimda' ? 'Pejabat Daerah' : ucwords(str_replace('-', ' ', $type));
        $data['type'] = $type;

        if ($type == 'forkopimda') {
            $officials = $model->whereIn('type', ['forkopimda', 'eselon-ii', 'eselon-iii', 'eselon-iv', 'kepala-desa'])
                               ->orderBy('type', 'ASC')
                               ->orderBy('order', 'ASC')
                               ->findAll();
            
            $data['groupedProfiles'] = [
                'Pejabat Daerah' => [],
                'Eselon II' => [],
                'Eselon III' => [],
                'Eselon IV' => [],
                'Kepala Desa' => []
            ];

            foreach ($officials as $official) {
                if ($official['type'] == 'forkopimda') $data['groupedProfiles']['Pejabat Daerah'][] = $official;
                elseif ($official['type'] == 'eselon-ii') $data['groupedProfiles']['Eselon II'][] = $official;
                elseif ($official['type'] == 'eselon-iii') $data['groupedProfiles']['Eselon III'][] = $official;
                elseif ($official['type'] == 'eselon-iv') $data['groupedProfiles']['Eselon IV'][] = $official;
                elseif ($official['type'] == 'kepala-desa') $data['groupedProfiles']['Kepala Desa'][] = $official;
            }
            
            return view('Frontend/profile_list', $data);
        } else {
            $data['profile'] = $model->where('type', $type)->orderBy('order', 'ASC')->orderBy('created_at', 'DESC')->first();
            return view('Frontend/profile_detail', $data);
        }
    }
}