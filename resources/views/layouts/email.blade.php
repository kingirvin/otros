<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">   
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter"/>
    <style>
    html, body {
        font-family: "Inter", "Arial", "sans-serif";
        font-size: 14px;
    }

    *, ::after, ::before {
        box-sizing: border-box;
    }

    .box-central {
        width: 100%;
        max-width: 450px;
        padding: 10px;
        margin: auto;
    }

    .text-justify{
        text-align: justify;
    }

    .text-center
    {
        text-align: center
    }

    .small, small {
        font-size: .875em;
    }

    .p-3 {
        padding: 1rem;
    }
    .pb-3 {
        padding-bottom: 1rem;
    }
    .pt-4 {
        padding-top: 1.5rem;
    }
    .px-3 {
        padding-right: 1rem;
        padding-left: 1rem;
    }
    .py-5 {
        padding-top: 3rem;
        padding-bottom: 3rem;
    }
    .py-2 {
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
    }
    .mb-2 {
        margin-bottom: 0.5rem;
    }
    .mb-3 {
        margin-bottom: 1rem;
    }
    .mb-4 {
        margin-bottom: 1.5rem;
    }
    .mb-5 {
        margin-bottom: 3rem;
    }
    .m-0 {
        margin: 0;
    }
    .lh-sm {
        line-height: 1.25;
    }
    .d-block {
        display: block;
    }
    .text-muted {
        color: #6c757d;
    }
    .fw-bolder {
        font-weight: bolder;
    }
    
    .h5, h5 {
        font-size: 1.25rem;
    }
    .h3, h3 {
        font-size: 1.75rem;
    }
    .bg-white {
        background-color: #fff;
    }
    .rounded {
        border-radius: 0.375rem;
    }
    .align-items-center {
        align-items: center;
    }
    .justify-content-evenly {
        justify-content: space-evenly;
    }
    .d-flex {
        display: flex;
    }

    .btn {        
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
        padding-right: 1rem;
        padding-left: 1rem;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5; 
        border-radius: 0.375rem;        
        display: inline-block;   
        text-align: center;
        text-decoration: none;
        vertical-align: middle;
        cursor: pointer;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;   
    }

    .btn-primary{
        color: #fff;
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    .btn-primary:hover
    {
        background-color: #0b5ed7;
    }
    
    </style>
</head>
<body style="background: #f8fafc;">
    <div class="text-center py-5">
        <div class="box-central">
            <img src="{{ asset('img/logo_horizontal.png') }}" alt="" height="65" class="mb-3">
            <div class="rounded p-3 mb-3" style="background: #c0e1c9">
                <div class="bg-white rounded px-3 pt-4 pb-3 mb-4">
                    @yield('contenido')
                </div>
                <div class="d-flex justify-content-evenly align-items-center">
                    <a href="mailto: oti@unamad.edu.pe" class="d-block text-center" style="text-decoration: none;">
                        <div class="h3 m-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-question-circle" viewBox="0 0 16 16" style="height: 25px; width: 25px;">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                <path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
                            </svg>
                        </div>            
                        Ayuda            
                    </a>
                    <a href="https://www.unamad.edu.pe/" target="_blank" class="d-block text-center" style="text-decoration: none;">
                        <div class="h3 m-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-globe" viewBox="0 0 16 16" style="height: 25px; width: 25px;">
                                <path d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm7.5-6.923c-.67.204-1.335.82-1.887 1.855A7.97 7.97 0 0 0 5.145 4H7.5V1.077zM4.09 4a9.267 9.267 0 0 1 .64-1.539 6.7 6.7 0 0 1 .597-.933A7.025 7.025 0 0 0 2.255 4H4.09zm-.582 3.5c.03-.877.138-1.718.312-2.5H1.674a6.958 6.958 0 0 0-.656 2.5h2.49zM4.847 5a12.5 12.5 0 0 0-.338 2.5H7.5V5H4.847zM8.5 5v2.5h2.99a12.495 12.495 0 0 0-.337-2.5H8.5zM4.51 8.5a12.5 12.5 0 0 0 .337 2.5H7.5V8.5H4.51zm3.99 0V11h2.653c.187-.765.306-1.608.338-2.5H8.5zM5.145 12c.138.386.295.744.468 1.068.552 1.035 1.218 1.65 1.887 1.855V12H5.145zm.182 2.472a6.696 6.696 0 0 1-.597-.933A9.268 9.268 0 0 1 4.09 12H2.255a7.024 7.024 0 0 0 3.072 2.472zM3.82 11a13.652 13.652 0 0 1-.312-2.5h-2.49c.062.89.291 1.733.656 2.5H3.82zm6.853 3.472A7.024 7.024 0 0 0 13.745 12H11.91a9.27 9.27 0 0 1-.64 1.539 6.688 6.688 0 0 1-.597.933zM8.5 12v2.923c.67-.204 1.335-.82 1.887-1.855.173-.324.33-.682.468-1.068H8.5zm3.68-1h2.146c.365-.767.594-1.61.656-2.5h-2.49a13.65 13.65 0 0 1-.312 2.5zm2.802-3.5a6.959 6.959 0 0 0-.656-2.5H12.18c.174.782.282 1.623.312 2.5h2.49zM11.27 2.461c.247.464.462.98.64 1.539h1.835a7.024 7.024 0 0 0-3.072-2.472c.218.284.418.598.597.933zM10.855 4a7.966 7.966 0 0 0-.468-1.068C9.835 1.897 9.17 1.282 8.5 1.077V4h2.355z"/>
                            </svg>
                        </div> 
                        Web                       
                    </a>
                    <a href="https://www.facebook.com/unamad.oficial/" target="_blank" class="d-block text-center" style="text-decoration: none;">
                        <div class="h3 m-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-facebook" viewBox="0 0 16 16" style="height: 25px; width: 25px;">
                                <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z"/>
                            </svg>
                        </div> 
                        Facebook                       
                    </a>
                </div>                
            </div>
            <small class="d-block text-muted lh-sm mb-3">
                Este es un correo enviado de forma automática, no es necesario responder a este mensaje, ya que procede de un buzón de correo desatendido.
            </small>            
            <small class="d-block text-muted lh-sm">
                UNAMAD &copy; 2022 &bull; Oficina de Tecnológias de la Información
            </small>
        </div>
    </div> 
</body>
</html>