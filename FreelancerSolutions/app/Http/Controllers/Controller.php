<?php

    namespace App\Http\Controllers;

    use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
    use Illuminate\Foundation\Validation\ValidatesRequests;
    use Illuminate\Routing\Controller as BaseController; // Importa o Controller base do Laravel

    class Controller extends BaseController // Estende o Controller base
    {
        use AuthorizesRequests, ValidatesRequests;

        // O construtor padrão da classe base já cuida da inicialização necessária.
        // Não é necessário definir um __construct() aqui a menos que você tenha uma lógica global específica.
        // Se você tiver um método __construct() aqui, certifique-se de chamar parent::__construct();
    }
