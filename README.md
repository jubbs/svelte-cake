# CakePHP Application Skeleton with Svelte client

Reproduce

Install cake 

create client folder in the root
npm create vite@latest
```
    Need to install the following packages:
    create-vite@4.4.0
    Ok to proceed? (y) 
    ✔ Project name: … vite-project
    ✔ Select a framework: › Svelte
    ✔ Select a variant: › JavaScript

    Scaffolding project in ...workspace/svelte-cake/client/vite-project...

    Done. Now run:

    cd vite-project
    npm install
    npm run dev
```
Configure the vite server proxy to point to the cake server
https://vitejs.dev/config/server-options.html#server-proxy

vite.config.js
```
import { defineConfig } from 'vite'
import { svelte } from '@sveltejs/vite-plugin-svelte'

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [svelte()],
  server: {
    proxy: {
      '/api': 'http://localhost:8765'
    }
  }
})
```
## Create the APIs in cake
https://book.cakephp.org/4/en/views/json-and-xml-views.html
https://www.youtube.com/watch?v=GZPUlQEnAkA&t=5s


Add the view classes to the controllers that you want
```
    public function viewClasses(): array
    {
        return [JsonView::class];
    }
```

The action must output json
```public function index()
    {
        $roles = $this->paginate($this->Roles);

        $this->set(compact('roles'));
        $this->viewBuilder()->setOption('serialize', 'roles');
    }
```

Add an api route to the routes.pjp
Needs to include any controllers that we want to access
```
     $routes->scope('/api', function (RouteBuilder $routes) {
        $routes->setExtensions(['json']);
        $routes->resources('Roles');
    });
```
Can either use the extension or the accept header application/json

