/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        'brand-blue': '#1e3a8a', // Warna biru navy sesuai gambar UI Anda
      },
    },
  },
  plugins: [],
}