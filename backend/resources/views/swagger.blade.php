<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sanatorio Médico | Swagger</title>
    <link rel="stylesheet" href="https://unpkg.com/swagger-ui-dist@5.31.0/swagger-ui.css">
</head>
<body>
    <div id="swagger-ui"></div>
    <script src="https://unpkg.com/swagger-ui-dist@5.31.0/swagger-ui-bundle.js"></script>
    <script>
        window.ui = SwaggerUIBundle({
            url: {{ Illuminate\Support\Js::from(route('swagger.spec')) }},
            dom_id: '#swagger-ui',
            deepLinking: true,
            validatorUrl: null,
            presets: [SwaggerUIBundle.presets.apis],
            requestInterceptor: function (request) {
                request.headers['Accept'] = 'application/json';
                return request;
            }
        });
    </script>
</body>
</html>
