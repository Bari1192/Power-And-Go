/** @type {import('tailwindcss').Config} */
export default {
  content: ["./index.html", "./src/**/*.{vue,js,ts,jsx,tsx}"],
  theme: {
    extend: {
      animation: {
        "up-down": "upDown 3s ease-in-out infinite",
      },
      keyframes: {
        upDown: {
          "0%": { transform: "translateY(-15px)" },
          "50%": { transform: "translateY(15px)" },
          "100%": { transform: "translateY(-15px)" },
        },
      },
    },
  },
  plugins: [
    import("@tailwindcss/aspect-ratio"),
    import("@tailwindcss/container-queries"),
    import("@tailwindcss/forms"),
    import("@tailwindcss/typography"),
  ],
};
