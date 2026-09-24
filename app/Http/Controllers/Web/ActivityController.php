<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\ActivityGpsPoint;
use App\Notifications\NewActivityNotification;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    // Liste des activités
    public function index()
    {
        $activities = Activity::where('user_id', auth()->id())
            ->orderBy('start_time', 'desc')
            ->paginate(15);

        return view('activities.index', compact('activities'));
    }

    // Formulaire de création
    public function create()
    {
        return view('activities.create');
    }

    // Enregistrer une activité
    public function store(Request $request)
    {
        $request->validate([
            'sport_type' => 'required|string',
            'start_time' => 'required|date',
            'name' => 'nullable|string|max:255',
            'duration_seconds' => 'nullable|integer',
            'distance_meters' => 'nullable|numeric',
            'calories_burned' => 'nullable|integer',
            'avg_heart_rate' => 'nullable|integer',
            'max_heart_rate' => 'nullable|integer',
            'elevation_gain' => 'nullable|numeric',
            'notes' => 'nullable|string',
            'perceived_effort' => 'nullable|integer|min:1|max:10',
        ]);

        $activity = new Activity();
        $activity->user_id = auth()->id();
        $activity->sport_type = $request->sport_type;
        $activity->name = $request->name;
        $activity->start_time = $request->start_time;
        $activity->duration_seconds = $request->duration_seconds;
        $activity->distance_meters = $request->distance_meters;
        $activity->calories_burned = $request->calories_burned;
        $activity->avg_heart_rate = $request->avg_heart_rate;
        $activity->max_heart_rate = $request->max_heart_rate;
        $activity->elevation_gain = $request->elevation_gain;
        $activity->notes = $request->notes;
        $activity->is_manual = true;
        $activity->is_private = $request->has('is_private') ? true : false;
        $activity->perceived_effort = $request->perceived_effort;
        $activity->save();

        // Envoyer notification aux followers
        foreach (auth()->user()->followers as $follower) {
            $follower->notify(new NewActivityNotification($activity));
        }

        return redirect('/activities/' . $activity->id)
            ->with('success', 'Activité ajoutée avec succès !');
    }

    // Détail d'une activité
    public function show($id)
    {
        $activity = Activity::with(['gpsPoints', 'user', 'comments.user', 'likes'])
            ->findOrFail($id);

        $hrData = $activity->gpsPoints()
            ->whereNotNull('heart_rate')
            ->select('timestamp', 'heart_rate')
            ->orderBy('timestamp')
            ->get();

        $speedData = $activity->gpsPoints()
            ->whereNotNull('speed')
            ->select('timestamp', 'speed')
            ->orderBy('timestamp')
            ->get();

        $altitudeData = $activity->gpsPoints()
            ->whereNotNull('altitude')
            ->select('timestamp', 'altitude')
            ->orderBy('timestamp')
            ->get();

        return view('activities.show', compact(
            'activity',
            'hrData',
            'speedData',
            'altitudeData'
        ));
    }

    // Ajouter un like
    public function toggleLike($id)
    {
        $activity = Activity::findOrFail($id);
        $existingLike = $activity->likes()->where('user_id', auth()->id())->first();

        if ($existingLike) {
            $existingLike->delete();
            return back()->with('success', 'Like retiré');
        } else {
            $activity->likes()->create(['user_id' => auth()->id()]);
            return back()->with('success', 'Like ajouté');
        }
    }

    // Ajouter un commentaire
    public function addComment(Request $request, $id)
    {
        $request->validate(['content' => 'required|string|max:500']);

        $activity = Activity::findOrFail($id);
        $activity->comments()->create([
            'user_id' => auth()->id(),
            'content' => $request->content,
        ]);

        return back()->with('success', 'Commentaire ajouté');
    }

    // Importer un fichier GPS
    public function importGps(Request $request, $id)
    {
        $request->validate(['gps_file' => 'required|file|mimes:gpx,xml,json']);

        $activity = Activity::findOrFail($id);
        $file = $request->file('gps_file');
        $content = file_get_contents($file);

        if ($file->getClientOriginalExtension() === 'gpx') {
            $xml = simplexml_load_string($content);
            $ns = $xml->getNamespaces(true);

            foreach ($xml->trk->trkseg->trkpt as $point) {
                $activity->gpsPoints()->create([
                    'timestamp' => (string)$point->time,
                    'latitude' => (float)$point['lat'],
                    'longitude' => (float)$point['lon'],
                    'altitude' => (float)$point->ele,
                    'heart_rate' => isset($ns['gpxtpx']) ? (int)$point->extensions->children($ns['gpxtpx'])->TrackPointExtension->hr : null,
                ]);
            }
        }

        return back()->with('success', 'Fichier GPS importé avec succès !');
    }
}
