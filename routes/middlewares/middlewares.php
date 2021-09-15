<?php
namespace routes\middlewares;

class Middleware {
    protected $routes_middelware = [
        "/perfil"=>[
            "session"=>"user",
            "redirect"=>"./"
        ],

        "/login"=>[
            "not"=>"user",
            "redirect"=>"./"
        ],

        "/register"=>[
            "not"=>"user",
            "redirect"=>"./"
        ]
    ];
    
    public function exec_middleware($pre_route, $middelware){
        if(isset($this->routes_middelware[$pre_route]) && isset($middelware["middleware"])){
            if(is_array($middelware["middleware"])){
                foreach ($middelware["middleware"] as $route => $func) {
                    $this->$func($pre_route);
                }
            }else{
                $func = $middelware["middleware"];
                $this->$func($pre_route);
            }
        }
    }

    public function use_session($route)
    {
        $session = $this->routes_middelware[$route]["session"];
        $redirect = $this->routes_middelware[$route]["redirect"];

        if(!(isset($_SESSION[$session]))){
            http_response_code(403);
            die("<script>window.location.href = '".$redirect."'</script>");
        }
    }
    
    public function not_session($route)
    {
        $session = $this->routes_middelware[$route]["not"];
        $redirect = $this->routes_middelware[$route]["redirect"];
        
        if(isset($_SESSION[$session])){
            die("<script>window.location.href = '".$redirect."'</script>");
        }
    }
}
?>