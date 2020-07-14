alkalmazás futtatása
    
    app és környezet indítása a "docker-compose up"-pal ebben a mappában

    utána composer install

    a localhost-od 80-as portján tudsz az apptól kérni

    egyetlen végpontja van az appnak

    az én esetemben a request így nézett ki:
    http://localhost/testwork/public?handle1=symfony&handle2=knplabs&method=fib
