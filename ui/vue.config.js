module.exports = {
    runtimeCompiler: true,
    productionSourceMap: false,
    configureWebpack: {
        // Change these settings based on your server network configuration
        // This default configuration is set to work with virtualbox port forwarding :
        // host IP/port 127.0.0.1/8080 , guest IP/port 10.0.2.15/8080
        // Of course this will also work if you have the dev server / environment setup
        // directly on your OS
        // Reachable at localhost:8080
        devServer: {
            host: '0.0.0.0',
            port: 8080,
            headers: {
                "Access-Control-Allow-Origin": "*"
            },
            allowedHosts: ['localhost', '127.0.0.1', '10.0.2.15'],
            public: 'localhost:8080'
        },
        // Adjust these settings based on your performance requirements
        // Is only relevant for the build/production chunks size
        // Size is in bytes
        performance: {
            maxEntrypointSize: 4000000,
            maxAssetSize: 1500000
        }
    }
};
