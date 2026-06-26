import { defineConfig } from 'vite'

/**
 * Build for the MelisCommerce React brick.
 *
 * Single IIFE bundle (public/ui-react/brick.js) loaded at runtime by the MelisCore React
 * shell when the module is active. React / ReactRouter are EXTERNAL (mapped to the host
 * globals exposed in MelisCore's main.tsx) so the brick reuses the host React instance
 * (hooks / Router / context all work across the boundary).
 *
 * Paths are RELATIVE (resolved from the config root = this dir) on purpose: `import.meta.dirname`
 * is undefined inside vite's esbuild-bundled config loader on the WSL/UNC mount, which made the
 * config throw and vite silently fall back to an index.html app build.
 */
export default defineConfig({
  esbuild: { jsx: 'automatic' },
  build: {
    outDir: '../public/ui-react',
    emptyOutDir: false,
    lib: {
      entry: 'src/brick.tsx',
      formats: ['iife'],
      name: 'MelisCommerceBrick',
      fileName: () => 'brick.js',
    },
    rollupOptions: {
      external: ['react', 'react-dom', 'react/jsx-runtime', 'react-router-dom'],
      output: {
        globals: {
          react: 'MelisReact',
          'react-dom': 'MelisReactDOM',
          'react/jsx-runtime': 'MelisReactJsxRuntime',
          'react-router-dom': 'MelisReactRouterDOM',
        },
      },
    },
  },
})
