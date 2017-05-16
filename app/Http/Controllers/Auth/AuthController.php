<?php namespace App\Http\Controllers\Auth;
use App\User;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
class AuthController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */
    use AuthenticatesAndRegistersUsers;
    protected $redirectPath = '/';
    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
    }
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'nick' => 'max:31',
            'first_name' => 'required|max:31',
            'last_name' => 'required|max:31',
            'address' => 'required|max:63',
            'postcode' => 'required|max:10',
            'city' => 'required|max:31',
            'telephone' => 'required|max:31',
            'email' => 'required|email|max:63|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data, $role_id = 0)
    {
        
        
        $user = User::create([
//            'nick' => $data['nick'],
            'email' => $data['email'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'telephone' => $data['telephone'],
            'city' => $data['city'],
            'address' => $data['address'],
            'postcode' => $data['postcode'],
            'password' => bcrypt($data['password']),
        ]);
        if($role_id <= 0){
            // role assign to new user until registration
            $role = \App\Role::where('name', '=', 'user')->take(1)->get();
            $user->roles()->attach($role->all()[0]->id);
        }
        
        
        return $user;
    }
    
    /**
    * Logowanie do aplikacji z podanym linkiem przekierowania.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function postLoginWithRedirect(Request $request, $redirect_path = null){
        $this->validate($request, [
            'email' => 'required|email', 'password' => 'required',
        ]);

        $credentials = $this->getCredentials($request);

        self::actionsBeforeLogin($redirect_path);
        
        if (\Auth::attempt($credentials, $request->has('remember'))) {
            self::actionsAfterLogging($redirect_path);
            return \Redirect::to($redirect_path);
        }

        return redirect($this->loginPath())
            ->withInput($request->only('email', 'remember'))
            ->withErrors([
                'email' => $this->getFailedLoginMessage(),
            ]);
    }
    /*
     * Zwykle logowanie do aplikacji, przekierowanie standardowe.
     */
    public function postLogin(Request $request){
        $this->validate($request, [
            'email' => 'required|email', 'password' => 'required',
        ]);

        $credentials = $this->getCredentials($request);

        \Session::flush();
        \Session::save();

        if (\Auth::attempt($credentials, $request->has('remember'))) {
            return \Redirect::to($this->redirectPath);
        }

        return redirect($this->loginPath())
            ->withInput($request->only('email', 'remember'))
            ->withErrors([
                'email' => $this->getFailedLoginMessage(),
            ]);
    }
    
    public function getLogout() {
        
        \Session::flush();
        \Session::save();
        
        
        \Auth::logout();
        return \Redirect::to(null);
    }
    
    public function _postRegister(Request $request, $redirect_path = null) {
        if($redirect_path)
            $this->redirectPath = $redirect_path;
        $this->postRegister($request);
        
        $this->validate($request, [
            'email' => 'required|email', 'password' => 'required',
        ]);
        $credentials = $this->getCredentials($request);
        
        if (\Auth::attempt($credentials)) {
            $this->actionsAfterLogging($redirect_path);
        }
        
    }
    
    
    private function actionsBeforeLogin($r_path){
        switch($r_path){
            case 'checkout':
                
                break;
        }
    }
    
    private function actionsAfterLogging($r_path){
        switch($r_path){
            case 'checkout':
                \App\Cart::setCurrentCartToLoggedUser();
                break;
            case 'register':
                dd('create_orer');
                break;
        }
    }
}