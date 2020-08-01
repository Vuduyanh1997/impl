<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use App\User;
use App\ReposDetail;
use DB;
use DataTables;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        $data = json_decode($user->github_data);
        return view('index', [
            'data' => $data
        ]);
    }

    public function viewRepos(){
        return view('repos.index');
    }
    public function getRepos(Request $request, $name, $per){
        $url = 'https://api.github.com/users/'.$name.'/repos?page=1&per_page='.$per;
        $url_all = 'https://api.github.com/users/'.$name.'/repos';
        $client = new \GuzzleHttp\Client();
        $request_api = $client->request('GET', $url);
        $request_api_all = $client->request('GET', $url_all);
        $response = static::contentsFromApi($request_api);
        $response_all = static::contentsFromApi($request_api_all);
        if (!empty($response)) {
            foreach ($response as $key => $res) {
                $updated_at = new Carbon($res['updated_at']);
                $response[$key]['updated_at'] = $updated_at->format('d/m/Y');
            }
        }
        $show = 0;
        $next = 0;
        if (count($response_all) - count($response) == 0) {
            $show = 1;
        }
        if (count($response_all) - count($response) >= 30) {
            $next = $per + 30;
        } else {
            $next = $per + (count($response_all) - count($response));
        }
        return view('repos.data', [
            'datas'      => $response,
            'count'      => count($response),
            'count_all'  => count($response_all),
            'name'       => $name,
            'next'       => $next,
            'show'       => $show,
        ]);
    }
    public function getReposClone(Request $request){
        DB::beginTransaction();
        try {
            $url = 'https://api.github.com/users/'.$request->name_search.'/repos';
            $client = new \GuzzleHttp\Client();
            $request_api = $client->request('GET', $url);
            $response = static::contentsFromApi($request_api);
            $repo_clone = null;
            if (!empty($response)) {
                foreach ($response as $key => $res) {
                    if ($res['id'] == $request->id) {
                        $repo_clone = json_encode($res);
                        ReposDetail::create([
                            'full_name'  => $res['full_name'],
                            'user_id'    => Auth::user()->id,
                            'data'       => $repo_clone,
                        ]);
                    }
                }
            }
            DB::commit();
            return response()->json([
                'error'   => false,
                'message' => 'Clone success',
            ]);
            
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'error'   => true,
                'message' => $e->getMessage()
            ]);
        }
        
    }

    public static function contentsFromApi($request) {
    
        $response_json = $request->getBody()->getContents();
        $response = json_decode($response_json, true);

        return $response;
    }

    public function getListRepos(){
        return view('repos.list');
    }

    public function getListReposClone(){
        $repos = DB::table('repos_details')->where('user_id', Auth::user()->id)->get();
        if ($repos->count() > 0) {
            foreach ($repos as $key => $repo) {
                $user = User::where('id', $repo->user_id)->first();
                $repo->user_name = $user->name;
                $repo->user_github_id = $user->github_id;
                $repo->data = json_decode($repo->data);
            }
        }
        return DataTables::of($repos)
            ->addIndexColumn()
            ->addColumn('action', function ($repo){
                $txt = "";
                $data = $repo->data;
                if ($repo->link_fork == null) {
                    // $txt .= '<a class="btn btn-success btn-xs btn-forks" data-user-github-id="'.$repo->user_github_id.'" data-id="'.$repo->id.'" data-forks="'.$data->forks_url.'"><i class="fas fa-code-branch"></i></a>';
                } else {
                    $txt .= '<a href="'.$repo->link_fork.'" target="_blank">'.$repo->link_fork.'</a>';
                }
                return $txt;
            })
            ->addColumn('name', function ($repo) {

                return $repo->full_name;
            })
            ->addColumn('user', function ($repo) {

                return $repo->user_name;
            })
            ->editColumn('created_at', function ($repo) {
                return date('H:i | d/m/Y', strtotime($repo->created_at));
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function fork(Request $request){
        $url = $request->forks_url;
        $client = new \GuzzleHttp\Client();
        $data = ['username'=>'bumblebiii', 'password'=>'Vuduyanh1997'];
        // dd(Auth::user()->github_token);
        $request_api = $client->request('POST', $url, [
            'headers' => [
                'Authorization' => 'Bearer 215ade78337329c70151788094deb72e6015a617',
                // 'Content-Type'=> 'application/json'
            ]
        ]);

        $response = static::contentsFromApi($request_api);
        dd($response);
    }
}
