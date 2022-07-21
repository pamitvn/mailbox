const mix = require('laravel-mix');
const { exec } = require('child_process');

mix.extend('ziggy', new class {
   register(config = {}) {
      this.watch = config.watch ?? ['routes/**/*.php'];
      this.path = config.path ?? '';
      this.enabled = config.enabled ?? !Mix.inProduction();
   }

   boot() {
      if (!this.enabled) return;

      const command = () => exec(`php artisan ziggy:generate ${this.path}`);

      command();

      if (Mix.isWatching() && this.watch) {
         ((require('chokidar')).watch(this.watch))
            .on('change', (path) => {
               console.log(`${path} changed...`);
               command();
            });
      }
   }
}());
