import LaravelEcho from 'laravel-echo';
import Pusher from '~/utils/pusher';

const Echo = new LaravelEcho({
    broadcaster: 'pusher',
    client: Pusher,
});

export default Echo;
