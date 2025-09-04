import { router } from '@inertiajs/vue3';

export function useLanguage() {
    const changeLanguage = (event) => {
        const newLocale = event.target.value;
        const currentPath = window.location.pathname.replace(/^\/(en|pl)/, '') || '/dashboard';
        router.visit(`/${newLocale}${currentPath}`);
    };

   return {
       changeLanguage
   };
}
