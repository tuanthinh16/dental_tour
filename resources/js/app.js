import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
const siteHeader = document.querySelector('[data-site-header]');

if (siteHeader) {
    let headerIsScrolled = false;

    const syncHeaderState = () => {
        const nextState = window.scrollY > 24;
        if (nextState === headerIsScrolled) return;

        headerIsScrolled = nextState;
        siteHeader.classList.toggle('is-scrolled', headerIsScrolled);
    };

    window.addEventListener('scroll', syncHeaderState, { passive: true });
    syncHeaderState();
}

document.querySelectorAll('[data-service-picker]').forEach((picker) => {
    const summary = picker.querySelector('[data-service-summary]');
    const options = [...picker.querySelectorAll('[data-service-option]')];

    const syncServiceSummary = () => {
        const selectedCount = options.filter((option) => option.checked).length;
        summary.textContent = selectedCount ? `${selectedCount} dịch vụ đã chọn` : 'Chọn dịch vụ';
    };

    options.forEach((option) => option.addEventListener('change', syncServiceSummary));
    syncServiceSummary();
});

document.querySelectorAll('[data-product-picker]').forEach((picker) => {
    const summary = picker.querySelector('[data-product-summary]');
    const options = [...picker.querySelectorAll('[data-product-option]')];

    const syncProductSummary = () => {
        const selectedCount = options.filter((option) => option.checked).length;
        summary.textContent = selectedCount ? `${selectedCount} sản phẩm đã chọn` : 'Chọn sản phẩm';
    };

    options.forEach((option) => option.addEventListener('change', syncProductSummary));
    syncProductSummary();
});

document.querySelectorAll('[data-image-input]').forEach((input) => {
    input.addEventListener('change', () => {
        const file = input.files?.[0];
        const preview = input.closest('label')?.querySelector('[data-image-preview]');
        if (!file || !preview) return;

        const image = new Image();
        const objectUrl = URL.createObjectURL(file);
        image.src = objectUrl;
        image.alt = 'Ảnh xem trước';
        image.className = 'h-full w-full object-cover';
        image.addEventListener('load', () => URL.revokeObjectURL(objectUrl), { once: true });
        preview.replaceChildren(image);
    });
});

document.querySelectorAll('[data-settings-tabs]').forEach((container) => {
    const buttons = [...container.querySelectorAll('[data-settings-tab]')];
    const panels = [...container.querySelectorAll('[data-settings-tab-panel]')];
    const setTab = (tab) => {
        panels.forEach((panel) => {
            panel.hidden = panel.dataset.settingsTabPanel !== tab;
        });
        buttons.forEach((button) => {
            const active = button.dataset.settingsTab === tab;
            button.classList.toggle('bg-slate-950', active);
            button.classList.toggle('text-white', active);
            button.classList.toggle('text-slate-500', !active);
            button.setAttribute('aria-pressed', String(active));
        });
    };

    setTab(container.dataset.initialTab === 'seo' ? 'seo' : 'theme');
    buttons.forEach((button) => button.addEventListener('click', () => setTab(button.dataset.settingsTab)));
});

document.querySelectorAll('[data-product-catalog]').forEach((catalog) => {
    const viewKey = 'dental-tour-product-view';
    const buttons = [...catalog.querySelectorAll('[data-product-view-switch]')];
    const views = [...catalog.querySelectorAll('[data-product-view]')];
    const setView = (view) => {
        views.forEach((element) => {
            element.hidden = element.dataset.productView !== view;
        });
        buttons.forEach((button) => {
            const active = button.dataset.productViewSwitch === view;
            button.setAttribute('aria-pressed', String(active));
            button.classList.toggle('bg-ink', active);
            button.classList.toggle('text-white', active);
            button.classList.toggle('text-ink/60', !active);
        });
        localStorage.setItem(viewKey, view);
    };

    const savedView = localStorage.getItem(viewKey);
    setView(savedView === 'card' ? 'card' : 'list');
    buttons.forEach((button) => button.addEventListener('click', () => setView(button.dataset.productViewSwitch)));
});

document.querySelectorAll('[data-product-dialog-open]').forEach((button) => {
    button.addEventListener('click', () => {
        const dialog = document.getElementById(button.dataset.productDialogOpen);
        if (!dialog) return;

        dialog.hidden = false;
        dialog.querySelector('input, textarea, select')?.focus();
    });
});

document.querySelectorAll('[data-product-dialog-close]').forEach((button) => {
    button.addEventListener('click', () => {
        document.getElementById(button.dataset.productDialogClose)?.setAttribute('hidden', 'hidden');
    });
});

document.querySelectorAll('[data-product-dialog]').forEach((dialog) => {
    dialog.addEventListener('click', (event) => {
        if (event.target === dialog) dialog.hidden = true;
    });
});

document.querySelectorAll('[data-visual-editor-open]').forEach((button) => {
    button.addEventListener('click', () => {
        const panel = document.getElementById(button.dataset.visualEditorOpen);
        if (!panel) return;

        panel.hidden = false;
        panel.closest('[draggable="true"]')?.setAttribute('draggable', 'false');
        button.setAttribute('aria-expanded', 'true');
        panel.querySelector('input, textarea, select')?.focus();
    });
});

document.querySelectorAll('[data-visual-editor-close]').forEach((button) => {
    button.addEventListener('click', () => {
        const panel = button.closest('[data-visual-editor-panel]');
        if (!panel) return;

        panel.hidden = true;
        panel.closest('[data-destination-sort-item]')?.setAttribute('draggable', 'true');
        document.querySelector(`[data-visual-editor-open="${panel.id}"]`)?.setAttribute('aria-expanded', 'false');
    });
});

const destinationSortList = document.querySelector('[data-destination-sort-list]');
const destinationSortForm = document.querySelector('[data-destination-sort-form]');
const destinationSortStatus = document.querySelector('[data-destination-sort-status]');

if (destinationSortList && destinationSortForm) {
    let draggedDestination = null;

    destinationSortList.addEventListener('dragstart', (event) => {
        draggedDestination = event.target.closest('[data-destination-sort-item]');
        if (!draggedDestination) return;

        draggedDestination.classList.add('opacity-45');
        event.dataTransfer.effectAllowed = 'move';
    });

    destinationSortList.addEventListener('dragover', (event) => {
        event.preventDefault();
        const target = event.target.closest('[data-destination-sort-item]');
        if (!draggedDestination || !target || target === draggedDestination) return;

        const bounds = target.getBoundingClientRect();
        const isVertical = window.innerWidth < 768;
        const insertAfter = isVertical
            ? event.clientY > bounds.top + bounds.height / 2
            : event.clientX > bounds.left + bounds.width / 2;
        destinationSortList.insertBefore(draggedDestination, insertAfter ? target.nextSibling : target);
    });

    destinationSortList.addEventListener('dragend', async () => {
        if (!draggedDestination) return;

        draggedDestination.classList.remove('opacity-45');
        draggedDestination = null;
        const data = new FormData(destinationSortForm);
        destinationSortList.querySelectorAll('[data-destination-sort-item]').forEach((item) => {
            data.append('destination_ids[]', item.dataset.destinationSortItem);
        });

        if (destinationSortStatus) destinationSortStatus.textContent = 'Đang lưu...';

        try {
            const response = await fetch(destinationSortForm.action, {
                method: 'POST',
                body: data,
                headers: { Accept: 'application/json' },
            });
            if (!response.ok) throw new Error('Không thể lưu thứ tự');
            if (destinationSortStatus) destinationSortStatus.textContent = 'Đã lưu';
        } catch {
            if (destinationSortStatus) destinationSortStatus.textContent = 'Lưu thất bại, hãy thử lại';
        }
    });
}

if (!reducedMotion) {
    const heroLines = document.querySelectorAll('[data-hero-line]');
    const heroActions = document.querySelectorAll('[data-hero-action]');

    if (heroLines.length) {
        gsap.from(heroLines, {
            yPercent: 115,
            duration: 1.25,
            stagger: 0.12,
            ease: 'power4.out',
        });
    }

    if (heroActions.length) {
        gsap.from(heroActions, {
            y: 24,
            opacity: 0,
            duration: 0.8,
            delay: 0.55,
            stagger: 0.1,
            ease: 'power3.out',
        });
    }

    document.querySelectorAll('[data-reveal-copy]').forEach((copy) => {
        const words = copy.querySelectorAll('.reveal-word');
        gsap.to(words, {
            opacity: 1,
            stagger: 0.035,
            ease: 'none',
            scrollTrigger: {
                trigger: copy,
                start: 'top 78%',
                end: 'bottom 42%',
                scrub: 1,
            },
        });
    });

    document.querySelectorAll('[data-tour-rail]').forEach((rail) => {
        const cards = [...rail.querySelectorAll('[data-tour-card]')];
        const controls = rail.closest('section')?.querySelector('[data-tour-rail-controls]');
        const scrollRail = (direction) => {
            rail.scrollBy({ left: rail.clientWidth * 0.84 * direction, behavior: 'smooth' });
        };

        controls?.querySelector('[data-tour-rail-prev]')?.addEventListener('click', () => scrollRail(-1));
        controls?.querySelector('[data-tour-rail-next]')?.addEventListener('click', () => scrollRail(1));
        rail.addEventListener('keydown', (event) => {
            if (event.key === 'ArrowLeft') scrollRail(-1);
            if (event.key === 'ArrowRight') scrollRail(1);
        });

        gsap.from(cards, {
            x: 64,
            opacity: 0,
            duration: 0.85,
            stagger: 0.1,
            ease: 'power3.out',
            scrollTrigger: { trigger: rail, start: 'top 84%' },
        });

        cards.forEach((card) => {
            const image = card.querySelector('[data-tour-card-image]');
            if (!image) return;

            gsap.fromTo(
                image,
                { scale: 0.94 },
                {
                    scale: 1,
                    ease: 'none',
                    scrollTrigger: {
                        trigger: rail,
                        start: 'top 88%',
                        end: 'top 40%',
                        scrub: 1,
                    },
                },
            );
        });
    });

    document.querySelectorAll('[data-motion-card]').forEach((card) => {
        gsap.from(card, {
            y: 70,
            opacity: 0,
            duration: 0.9,
            ease: 'power3.out',
            scrollTrigger: { trigger: card, start: 'top 88%' },
        });
    });

    document.querySelectorAll('[data-review-list]').forEach((list) => {
        const reviews = list.querySelectorAll('[data-review-item]');
        if (!reviews.length) return;

        gsap.from(reviews, {
            y: 20,
            opacity: 0,
            duration: reducedMotion ? 0 : 0.55,
            stagger: reducedMotion ? 0 : 0.1,
            ease: 'power3.out',
            scrollTrigger: { trigger: list, start: 'top 86%' },
        });
    });
}

window.addEventListener('load', () => ScrollTrigger.refresh());

document.querySelectorAll('[data-theme-marquee]').forEach((track) => {
    if (reducedMotion) return;

    gsap.to(track, {
        xPercent: -50,
        duration: 14,
        repeat: -1,
        ease: 'none',
    });
});

const themeForm = document.querySelector('[data-theme-form]');
const themePreview = document.querySelector('[data-theme-preview]');
const themePreviewModal = document.querySelector('[data-theme-preview-modal]');
const themePreviewDialog = document.querySelector('[data-theme-preview-dialog]');
const themePreviewOpen = document.querySelector('[data-theme-preview-open]');

if (themeForm && themePreview) {
    const fontStacks = {
        Satoshi: "'Satoshi', ui-sans-serif, system-ui, sans-serif",
        Outfit: "'Outfit', ui-sans-serif, system-ui, sans-serif",
        Geist: "'Geist', ui-sans-serif, system-ui, sans-serif",
        Manrope: "'Manrope', ui-sans-serif, system-ui, sans-serif",
        'DM Sans': "'DM Sans', ui-sans-serif, system-ui, sans-serif",
        'Playfair Display': "'Playfair Display', ui-serif, Georgia, serif",

        // Thêm 2026-08-30: đồng bộ font preview với bộ typography mặc định mới.
        'Be Vietnam Pro': "'Be Vietnam Pro', ui-sans-serif, system-ui, sans-serif",
        Lora: "'Lora', ui-serif, Georgia, serif",
        Inter: "'Inter', ui-sans-serif, system-ui, sans-serif",
    };

    const contrastText = (hex) => {
        const value = hex.replace('#', '');
        const channels = [value.slice(0, 2), value.slice(2, 4), value.slice(4, 6)].map((channel) => {
            const normalized = Number.parseInt(channel, 16) / 255;
            return normalized <= 0.04045 ? normalized / 12.92 : ((normalized + 0.055) / 1.055) ** 2.4;
        });
        const luminance = channels[0] * 0.2126 + channels[1] * 0.7152 + channels[2] * 0.0722;
        const whiteContrast = 1.05 / (luminance + 0.05);
        const darkContrast = (luminance + 0.05) / 0.05;

        return darkContrast >= whiteContrast ? '#0B1F1B' : '#FFFFFF';
    };

    const syncColors = () => {
        themeForm.querySelectorAll('[data-theme-control]').forEach((control) => {
            themePreview.style.setProperty(control.dataset.cssVariable, control.value);
            const valueLabel = control.closest('label')?.querySelector('[data-color-value]');
            if (valueLabel) valueLabel.textContent = control.value.toUpperCase();
        });

        const primary = themeForm.elements.ui_color_primary.value;
        const accent = themeForm.elements.ui_color_accent.value;
        const text = themeForm.elements.ui_color_text.value;
        themePreview.style.setProperty('--ui-color-primary-contrast', contrastText(primary));
        themePreview.style.setProperty('--ui-color-accent-contrast', contrastText(accent));
        themePreview.style.setProperty('--ui-color-text-contrast', contrastText(text));
    };

    themeForm.querySelectorAll('[data-theme-control]').forEach((control) => {
        control.addEventListener('input', syncColors);
    });

    themeForm.querySelectorAll('[data-theme-font]').forEach((control) => {
        const syncFont = () => {
            const fontStack = fontStacks[control.value];
            const inlinePreview = themeForm.querySelector(`[data-font-preview="${control.dataset.fontKey}"]`);

            themePreview.style.setProperty(control.dataset.cssVariable, fontStack);
            if (inlinePreview) inlinePreview.style.fontFamily = fontStack;
        };
        control.addEventListener('change', syncFont);
        syncFont();
    });

    themeForm.querySelector('[data-theme-reset]')?.addEventListener('click', () => {
        themeForm.querySelectorAll('[data-default]').forEach((control) => {
            control.value = control.dataset.default;
            control.dispatchEvent(new Event(control.matches('select') ? 'change' : 'input'));
        });
    });

    syncColors();
}

if (themePreviewModal && themePreviewDialog && themePreviewOpen) {
    let previewIsOpen = false;
    let previousBodyOverflow = '';

    const openThemePreview = () => {
        if (previewIsOpen) return;

        previewIsOpen = true;
        previousBodyOverflow = document.body.style.overflow;
        document.body.style.overflow = 'hidden';
        themePreviewModal.classList.remove('hidden');

        if (reducedMotion) {
            gsap.set(themePreviewDialog, { clearProps: 'all' });
        } else {
            gsap.fromTo(
                themePreviewDialog,
                { opacity: 0, y: 28, scale: 0.985 },
                { opacity: 1, y: 0, scale: 1, duration: 0.45, ease: 'power3.out' },
            );
        }

        themePreviewModal.querySelector('[data-theme-preview-close-button]')?.focus();
        ScrollTrigger.refresh();
    };

    const closeThemePreview = () => {
        if (!previewIsOpen) return;

        const finishClosing = () => {
            previewIsOpen = false;
            themePreviewModal.classList.add('hidden');
            document.body.style.overflow = previousBodyOverflow;
            gsap.set(themePreviewDialog, { clearProps: 'all' });
            themePreviewOpen.focus();
        };

        if (reducedMotion) {
            finishClosing();
            return;
        }

        gsap.to(themePreviewDialog, {
            opacity: 0,
            y: 20,
            scale: 0.99,
            duration: 0.25,
            ease: 'power2.in',
            onComplete: finishClosing,
        });
    };

    themePreviewOpen.addEventListener('click', openThemePreview);
    themePreviewModal.querySelectorAll('[data-theme-preview-close]').forEach((button) => {
        button.addEventListener('click', closeThemePreview);
    });
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && previewIsOpen) closeThemePreview();
    });
}

const testimonials = [...document.querySelectorAll('[data-testimonial]')];
let testimonialIndex = 0;

function showTestimonial(nextIndex) {
    if (!testimonials.length || nextIndex === testimonialIndex) return;

    const current = testimonials[testimonialIndex];
    const next = testimonials[nextIndex];

    gsap.to(current, {
        opacity: 0,
        y: -18,
        duration: reducedMotion ? 0 : 0.3,
        onComplete: () => current.classList.add('hidden'),
    });
    next.classList.remove('hidden');
    gsap.fromTo(
        next,
        { opacity: 0, y: 18 },
        { opacity: 1, y: 0, duration: reducedMotion ? 0 : 0.5, ease: 'power3.out' },
    );
    testimonialIndex = nextIndex;
}

document.querySelector('[data-testimonial-next]')?.addEventListener('click', () => {
    showTestimonial((testimonialIndex + 1) % testimonials.length);
});

document.querySelector('[data-testimonial-prev]')?.addEventListener('click', () => {
    showTestimonial((testimonialIndex - 1 + testimonials.length) % testimonials.length);
});
