<?php

declare(strict_types=1);

return [
    /*
     * ------------------------------------------------------------------------
     * Default Firebase project
     * ------------------------------------------------------------------------
     */

    'default' => env('FIREBASE_PROJECT', 'app'),

    /*
     * ------------------------------------------------------------------------
     * Firebase project configurations
     * ------------------------------------------------------------------------
     */

    'projects' => [
        'app' => [

            /*
             * ------------------------------------------------------------------------
             * Credentials / Service Account
             * ------------------------------------------------------------------------
             *
             * In order to access a Firebase project and its related services using a
             * server SDK, requests must be authenticated. For server-to-server
             * communication this is done with a Service Account.
             *
             * If you don't already have generated a Service Account, you can do so by
             * following the instructions from the official documentation pages at
             *
             * https://firebase.google.com/docs/admin/setup#initialize_the_sdk
             *
             * Once you have downloaded the Service Account JSON file, you can use it
             * to configure the package.
             *
             * If you don't provide credentials, the Firebase Admin SDK will try to
             * auto-discover them
             *
             * - by checking the environment variable FIREBASE_CREDENTIALS
             * - by checking the environment variable GOOGLE_APPLICATION_CREDENTIALS
             * - by trying to find Google's well known file
             * - by checking if the application is running on GCE/GCP
             *
             * If no credentials file can be found, an exception will be thrown the
             * first time you try to access a component of the Firebase Admin SDK.
             *
             */
             
            'credentials' => env('FIREBASE_CREDENTIALS'),

            /*เนื่องจากไม่สามารถอ่านไฟล์ firebase_credentials.json ได้ เลยเอามาระบุในนี้โดยตรงแทน*/
            /*'credentials' => [
                'type' => 'service_account',
                'project_id' => 'projact-chat-business',
                'private_key_id' => '0f44dc9bfc4afc7005562737298d8e287483df4f',
                'private_key' => '-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDBEICXL6x14Si2\n+Z4wpJ94UP2mH7ixkO/GTdYyJnDcOwP8jJ3Tkykn4Aj1yKiggkMSPUvsiiQXT1+x\nHAsz5DrOAcYdvFAL9Z4hMhc3eJe3kUrSBss7D/Va62hP/8nZ16nmohFHaexYTHKu\nFgqutrkxjzEMBeQmvh1ih+q86kY54HE76jWxav3HrlLfU28NH2XtY6iQDkqQpnHM\nloFMh2v59uPIxr3FLMmTPREltwD+FcAp37UoE2WqPkdLNot/odfBhdlI+Qb2ETie\n2qst5vvNfuFZ2rK7XZCBn9Opdo/sF8nG0TdQL0KN50dfpjQqsSZUretWaPijOLVR\nKD0o+SK1AgMBAAECggEAJ7KAdlXHpM9gw0rNQ5w97AvfM97W56/xFXngvpwX3bjG\nx5GUTDI4pqnSvdL+FU7jgqcW6vK2nBJmjzDAfvGBQ+WqemfDn8nns1Ss4GuL6qNV\n2pyqAiF0Zjb8UmK6eu/0z+boHaKChd9mPzRqCuWoa6/ROnMlp++0dBOVaTXUrcaE\npHbRwnI9H0+owEaD+CXkom1aihWuuE3DmI0z/4Om2lsfwiLeCVhkcK37DwyetOxR\nV3QVejBkGcK1bY3JKo8yusokyGMyd1mpQeRVqq+RB2DzZAHHa6rSs7JqFAwWW463\npc94ojMiT8rg3TTXf5K6aGqFK+TWPsdwR4rmlM4kAQKBgQDf+Wc20LCB01O9jsek\nLxK9f6xNBUga9O88Sv/FOlXHLi5aFwq48QMk1M+XpfS7zT/NX7kxqkZbFSh+vp8V\nGkF7XGiwXItHxSn5Yz7C9FP2YuCdHaA1CClFlXQGS97q5G5+Kj9rhxnJeY6KrCaz\nW4BbUUH5skrhNbj9WZNmL+aUtQKBgQDcq6TuJ7GEH1llDYl6x9M2u3kVqULGX0j8\n3mxyBkBNgxvsyhsUKkeH1BE1gbPk00+Vf2DAaTg/dXescm2Y8h8b1RjCtQ0x9CM4\nScxgw2VzpAKHqHG1XRC0FR4tAkma33/R7rZR1nGIyrCgNBGeOn5LHq8xdIKCY/8m\n/32L6P8WAQKBgGZ6VNFt1vTv3mbjB1GGAEsYOZvcCMvcugGaR0DUmh7ScH7kABHy\npH+bp4g5dKrhFIpBfBjPUfWmzqp/SYZ8Ru3MsFHRZiDmg3gKAtNsu2YGg8MfpQfe\nvhOKeFXRLSPIdQ3hAreOTywyJBgrAGIcQbSGj2tSOpSk4gl5jMm6rQPpAoGBAI2o\nZlU4B/3v/fwgB4xZMN6m1KEyKhqcWodx5Z4BI1BfBRMp3t5AEfFBEtcNb0VK3YDz\n51E9eo1KREvgnTic3ZmJX77GaTUTK3Tb8yZ+6Oamd7VMTcqGds2T7O4p1MRmC3Dh\n1AqPJg0RSPde1ZyEokRo60BNMeaweMA5LQgex3oBAoGARBubm1SqszjCvcLCFXhL\nN9eCMTJTnR1IZei8s5mtAGvldO8JswePcjJYMIz3Znnb4Aec0dC9VK70JCX+TkdQ\niJQigB/cPJN+74iYEaQhYg+2/DEDmIkGrqdPHgJgJ79Q9z58LtU4orUTxl+ibgrR\nDkU0y6fuxiS87LWpwZQrNl0=\n-----END PRIVATE KEY-----\n',
                'client_email' => 'firebase-adminsdk-jtpnt@projact-chat-business.iam.gserviceaccount.com',
                'client_id' => '105151262710816575443',
                'auth_uri' => 'https://accounts.google.com/o/oauth2/auth',
                'token_uri' => 'https://oauth2.googleapis.com/token',
                'auth_provider_x509_cert_url' => 'https://www.googleapis.com/oauth2/v1/certs',
                'client_x509_cert_url' => 'https://www.googleapis.com/robot/v1/metadata/x509/firebase-adminsdk-jtpnt%40projact-chat-business.iam.gserviceaccount.com',
                'universe_domain' => 'googleapis.com'
            ],*/

            /*
             * ------------------------------------------------------------------------
             * Firebase Auth Component
             * ------------------------------------------------------------------------
             */

            'auth' => [
                'tenant_id' => env('FIREBASE_AUTH_TENANT_ID'),
            ],

            /*
             * ------------------------------------------------------------------------
             * Firestore Component
             * ------------------------------------------------------------------------
             */

            'firestore' => [

                /*
                 * If you want to access a Firestore database other than the default database,
                 * enter its name here.
                 *
                 * By default, the Firestore client will connect to the `(default)` database.
                 *
                 * https://firebase.google.com/docs/firestore/manage-databases
                 */

                // 'database' => env('FIREBASE_FIRESTORE_DATABASE'),
            ],

            /*
             * ------------------------------------------------------------------------
             * Firebase Realtime Database
             * ------------------------------------------------------------------------
             */

            'database' => [

                /*
                 * In most of the cases the project ID defined in the credentials file
                 * determines the URL of your project's Realtime Database. If the
                 * connection to the Realtime Database fails, you can override
                 * its URL with the value you see at
                 *
                 * https://console.firebase.google.com/u/1/project/_/database
                 *
                 * Please make sure that you use a full URL like, for example,
                 * https://my-project-id.firebaseio.com
                 */

                'url' => env('FIREBASE_DATABASE_URL'),

                /*
                 * As a best practice, a service should have access to only the resources it needs.
                 * To get more fine-grained control over the resources a Firebase app instance can access,
                 * use a unique identifier in your Security Rules to represent your service.
                 *
                 * https://firebase.google.com/docs/database/admin/start#authenticate-with-limited-privileges
                 */

                // 'auth_variable_override' => [
                //     'uid' => 'my-service-worker'
                // ],

            ],

            'dynamic_links' => [

                /*
                 * Dynamic links can be built with any URL prefix registered on
                 *
                 * https://console.firebase.google.com/u/1/project/_/durablelinks/links/
                 *
                 * You can define one of those domains as the default for new Dynamic
                 * Links created within your project.
                 *
                 * The value must be a valid domain, for example,
                 * https://example.page.link
                 */

                'default_domain' => env('FIREBASE_DYNAMIC_LINKS_DEFAULT_DOMAIN'),
            ],

            /*
             * ------------------------------------------------------------------------
             * Firebase Cloud Storage
             * ------------------------------------------------------------------------
             */

            'storage' => [

                /*
                 * Your project's default storage bucket usually uses the project ID
                 * as its name. If you have multiple storage buckets and want to
                 * use another one as the default for your application, you can
                 * override it here.
                 */

                'default_bucket' => env('FIREBASE_STORAGE_DEFAULT_BUCKET'),

            ],

            /*
             * ------------------------------------------------------------------------
             * Caching
             * ------------------------------------------------------------------------
             *
             * The Firebase Admin SDK can cache some data returned from the Firebase
             * API, for example Google's public keys used to verify ID tokens.
             *
             */

            'cache_store' => env('FIREBASE_CACHE_STORE', 'file'),

            /*
             * ------------------------------------------------------------------------
             * Logging
             * ------------------------------------------------------------------------
             *
             * Enable logging of HTTP interaction for insights and/or debugging.
             *
             * Log channels are defined in config/logging.php
             *
             * Successful HTTP messages are logged with the log level 'info'.
             * Failed HTTP messages are logged with the log level 'notice'.
             *
             * Note: Using the same channel for simple and debug logs will result in
             * two entries per request and response.
             */

            'logging' => [
                'http_log_channel' => env('FIREBASE_HTTP_LOG_CHANNEL'),
                'http_debug_log_channel' => env('FIREBASE_HTTP_DEBUG_LOG_CHANNEL'),
            ],

            /*
             * ------------------------------------------------------------------------
             * HTTP Client Options
             * ------------------------------------------------------------------------
             *
             * Behavior of the HTTP Client performing the API requests
             */

            'http_client_options' => [

                /*
                 * Use a proxy that all API requests should be passed through.
                 * (default: none)
                 */

                'proxy' => env('FIREBASE_HTTP_CLIENT_PROXY'),

                /*
                 * Set the maximum amount of seconds (float) that can pass before
                 * a request is considered timed out
                 *
                 * The default time out can be reviewed at
                 * https://github.com/kreait/firebase-php/blob/6.x/src/Firebase/Http/HttpClientOptions.php
                 */

                'timeout' => env('FIREBASE_HTTP_CLIENT_TIMEOUT'),

                'guzzle_middlewares' => [],
            ],
        ],
    ],
];
