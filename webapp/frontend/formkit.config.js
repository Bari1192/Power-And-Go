import { defaultConfig } from "@formkit/vue";
import { generateClasses } from "@formkit/themes";

export default defaultConfig({
  locales: {
    hu: {
      validation: {
        integer: "Csak egész szám adható meg a mezőben.",
      },
    },
  },
  locale: "hu",
  messages: {
    hu: {
      ui: {
        incomplete: "Kérjük, töltsd ki az összes mezőt helyesen!",
        cleanreportIncomplete: "Kérjük, töltse ki az összes mezőt helyesen!",
        cleanreport: "Kérjük, töltse ki az összes mezőt helyesen!",
        submitIncomplete:
          "Minden mezőt helyesen kell kitölteni a folytatáshoz.",
      },
    },
  },
  config: {
    classes: generateClasses({
      global: {},
      label: {
        label: "text-lg font-bold text-sky-300 mb-2",
      },
      select: {
        input: "form-select mx-auto",
      },
      radio: {
        input: "form-radio",
      },
      checkbox: {
        input: "form-checkbox",
      },
      textarea: {
        input: "form-textarea text-sky-800 font-semibold",
      },
      submit: {
        submit: "submit",
      },
    }),
  },
});
