<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Mission;
use App\Models\Visi;
use App\Models\Vision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CandidateController extends Controller
{
    public function index()
    {
        $candidates = Candidate::orderBy('sequence', 'ASC')
            ->get();

        $data = [];

        foreach ($candidates as $candidate) {
            $data[] = [
                'id' => $candidate->id,
                'sequence' => $candidate->sequence,
                'chairman' => $candidate->chairman,
                'deputy_chairman' => $candidate->deputy_chairman,
                'photo_chairman' => Storage::disk('public')->url($candidate->photo_chairman),
                'photo_deputy_chairman' => Storage::disk('public')->url($candidate->photo_deputy_chairman),
            ];
        }

        return $this->sendSuccess($data, 'successfully load candidates', 200);
    }

    public function getListVision($id)
    {
        $candidate = Candidate::find($id);

        if (!$candidate) {
            return $this->sendError('failed to load visions', 400);
        }

        $visions = Vision::where('candidate_id', $candidate->id)
            ->orderBy('sequence', 'ASC')
            ->get();


        $data = [];

        foreach ($visions as $vision) {
            $data[] = [
                'id' => $vision->id,
                'sequence' => $vision->sequence,
                'description' => $vision->description,
            ];
        }

        return $this->sendSuccess($data, 'successfully load visions', 200);
    }

    public function getListMission($id)
    {
        $candidate = Candidate::find($id);

        if (!$candidate) {
            return $this->sendError('failed to load missions', 400);
        }

        $missions = Mission::where('candidate_id', $candidate->id)
            ->orderBy('sequence', 'ASC')
            ->get();


        $data = [];

        foreach ($missions as $mission) {
            $data[] = [
                'id' => $mission->id,
                'sequence' => $mission->sequence,
                'description' => $mission->description,
            ];
        }

        return $this->sendSuccess($data, 'successfully load visions', 200);
    }

    public function show($id)
    {
        $candidate = Candidate::find($id);

        if (!$candidate) {
            return $this->sendError('failed to load candidate', 400);
        }

        return $this->sendSuccess([
            'id' => $candidate->id,
            'sequence' => $candidate->sequence,
            'chairman' => $candidate->chairman,
            'deputy_chairman' => $candidate->deputy_chairman,
            'photo_chairman' => Storage::disk('public')->url($candidate->photo_chairman),
            'photo_deputy_chairman' => Storage::disk('public')->url($candidate->photo_deputy_chairman),
        ], 'successfully load candidate', 200);
    }

    public function getDetail($id)
    {
        $candidate = Candidate::find($id);

        if (!$candidate) {
            return $this->sendError('failed to load candidate', 400);
        }

        $visions = Vision::where('candidate_id', $candidate->id)->get();
        $missions = Mission::where('candidate_id', $candidate->id)->get();

        $visi = [];
        $misi = [];

        foreach ($visions as $vision) {
            $visi[] = [
                'id' => $vision->id,
                'sequence' => $vision->sequence,
                'description' => $vision->description,
            ];
        }

        foreach ($missions as $mission) {
            $misi[] = [
                'id' => $mission->id,
                'sequence' => $mission->sequence,
                'description' => $mission->description,
            ];
        }

        return $this->sendSuccess([
            'id' => $candidate->id,
            'sequence' => $candidate->sequence,
            'chairman' => $candidate->chairman,
            'deputy_chairman' => $candidate->deputy_chairman,
            'photo_chairman' => Storage::disk('public')->url($candidate->photo_chairman),
            'photo_deputy_chairman' => Storage::disk('public')->url($candidate->photo_deputy_chairman),
            'visions' => $visi,
            'missions' => $misi
        ], 'successfully load data', 200);
    }
}