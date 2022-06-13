import LaravelEcho from 'laravel-echo';
import PusherJs from 'pusher-js';

const Echo = new LaravelEcho({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    wsHost: process.env.MIX_PUSHER_HOST,
    wsPort: process.env.MIX_PUSHER_PORT,
    forceTLS: false,
    client: PusherJs,
});

export default Echo;
