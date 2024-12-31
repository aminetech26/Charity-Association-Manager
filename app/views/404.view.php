<!DOCTYPE html>
<html lang="fr" class="h-full bg-white dark:bg-gray-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Page d'erreur 404 - Contenu introuvable">
    <title>Page Introuvable - 404</title>
    <link rel="stylesheet" href="<?= ROOT . "public/assets/css/main.css?v=" . time() ?>">
</head>
<body class="h-full flex flex-col">
<section class="bg-white dark:bg-gray-900">
    <div class="py-8 px-4 mx-auto max-w-screen-xl lg:py-16 lg:px-6">
        <div class="mx-auto max-w-screen-sm text-center">
            <h1 class="mb-4 text-7xl tracking-tight font-extrabold lg:text-9xl text-primary dark:text-primary-500">404</h1>
            <p class="mb-4 text-3xl tracking-tight font-bold text-gray-900 md:text-4xl dark:text-white">Il manque quelque chose.</p>
            <p class="mb-4 text-lg font-light text-gray-500 dark:text-gray-400">Désolé, nous ne trouvons pas cette page. Vous trouverez beaucoup à explorer sur la page d'accueil.</p>
            <a href="<?= ROOT . "public/Home/" ?>" class="inline-flex text-white bg-primary hover:bg-primary-hover focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:focus:ring-primary-900 my-4">Retour à l'accueil</a>
        </div>   
    </div>
</section>
</body>
</html>
