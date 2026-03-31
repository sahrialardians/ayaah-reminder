<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { dashboard, login, register } from '@/routes';
import AppLogo from '@/components/AppLogo.vue';

withDefaults(
    defineProps<{
        canRegister: boolean;
    }>(),
    {
        canRegister: true,
    },
);

const faqs = [
    {
        question: "Is AyaahReminder really free?",
        answer: "Yes, our core features—tracking your reading and daily email reminders—are completely free. Our mission is to help the Ummah stay connected with the Qur'an."
    },
    {
        question: "Can I change my reminder time later?",
        answer: "Absolutely! You can update your preferred reminder time and timezone whenever you like from your account settings."
    },
    {
        question: "Does it work on mobile?",
        answer: "Yes, AyaahReminder is designed to be mobile-first. You can easily save your progress from your phone right after you finish reading."
    },
    {
        question: "Is my data private?",
        answer: "We take your spiritual journey seriously. Your reading history is private to you, and we never sell your data to third parties."
    }
];

const testimonials = [
    {
        name: "Omar K.",
        role: "Student",
        quote: "I used to lose my bookmark all the time. Now, I just tap a button on my phone and I'm done. It's so much easier."
    },
    {
        name: "Fatima S.",
        role: "Working Professional",
        quote: "The daily email reminder is the highlight of my morning. It's that gentle nudge I need to stay consistent during a busy week."
    },
    {
        name: "Ahmed R.",
        role: "Parent",
        quote: "Helping my kids track their progress has become a fun daily activity. We love seeing our history grow."
    }
];
</script>

<template>
    <Head title="Ayaah Reminder - Never Lose Your Place in the Qur'an Again">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>

    <div class="flex min-h-screen flex-col bg-background text-foreground selection:bg-primary selection:text-primary-foreground font-sans overflow-x-hidden">
        <!-- Navigation -->
        <header class="sticky top-0 z-50 w-full border-b bg-background/95 backdrop-blur supports-[backdrop-filter]:bg-background/60">
            <div class="container mx-auto flex h-16 items-center justify-between px-4 lg:px-8">
                <AppLogo />
                
                <nav class="flex items-center gap-4 lg:gap-6">
                    <template v-if="$page.props.auth.user">
                        <Link :href="dashboard()" class="text-sm font-medium transition-colors hover:text-primary">Dashboard</Link>
                    </template>
                    <template v-else>
                        <Link :href="login()" class="text-sm font-medium transition-colors hover:text-primary hidden sm:block">Log in</Link>
                        <Link v-if="canRegister" :href="register()" class="inline-flex h-9 items-center justify-center rounded-full bg-primary px-5 py-2 text-sm font-bold text-primary-foreground shadow transition-colors hover:bg-primary/90">
                            Join for Free
                        </Link>
                    </template>
                </nav>
            </div>
        </header>

        <main class="flex-1">
            <!-- Hero Section -->
            <section class="container mx-auto flex flex-col items-center gap-6 py-16 px-6 text-center lg:py-40 lg:px-8">
                <div class="flex flex-col gap-4 sm:gap-6">
                    <div class="inline-flex items-center rounded-full border px-3 py-1 text-[10px] sm:text-xs font-medium text-primary bg-primary/5 mx-auto animate-in fade-in slide-in-from-bottom-3 duration-1000">
                        ✨ Small habits, immense rewards.
                    </div>
                    <h1 class="text-4xl font-extrabold tracking-tight sm:text-6xl lg:text-8xl max-w-5xl mx-auto leading-[1.1] sm:leading-[0.95]">
                        The Bookmark that <span class="text-primary italic">Reminds</span> You
                    </h1>
                    <p class="mx-auto max-w-[750px] text-base text-muted-foreground sm:text-xl lg:text-2xl leading-relaxed mt-2 sm:mt-4">
                        Never lose your place again. Track your daily Ayah and receive gentle, timely reminders to stay connected with the Book of Allah.
                    </p>
                </div>
                
                <div class="flex flex-col w-full sm:w-auto gap-3 sm:flex-row mt-4 sm:mt-6">
                    <Link
                        v-if="canRegister && !$page.props.auth.user"
                        :href="register()"
                        class="inline-flex h-14 sm:h-16 items-center justify-center rounded-full bg-primary px-8 sm:px-12 py-4 text-lg sm:text-xl font-bold text-primary-foreground shadow-2xl transition-all hover:bg-primary/90 hover:scale-105 active:scale-95"
                    >
                        Start Your Habit Today
                    </Link>
                    <Link
                        v-if="$page.props.auth.user"
                        :href="dashboard()"
                        class="inline-flex h-14 sm:h-16 items-center justify-center rounded-full bg-primary px-8 sm:px-12 py-4 text-lg sm:text-xl font-bold text-primary-foreground shadow-2xl transition-all hover:bg-primary/90 hover:scale-105 active:scale-95"
                    >
                        Go to Dashboard
                    </Link>
                </div>
                <p class="text-xs sm:text-sm text-muted-foreground">No credit card required. Ever.</p>
            </section>

            <!-- Pain Points Section -->
            <section class="py-24 bg-primary text-primary-foreground">
                <div class="container mx-auto px-4 lg:px-8">
                    <div class="grid lg:grid-cols-2 gap-16 items-center">
                        <div class="space-y-8">
                            <h2 class="text-4xl font-extrabold tracking-tight sm:text-6xl leading-[1.1]">The struggle is real. We've all been there.</h2>
                            <ul class="space-y-6">
                                <li class="flex items-start gap-4">
                                    <div class="h-6 w-6 rounded-full bg-white/20 flex items-center justify-center shrink-0 mt-1">✕</div>
                                    <p class="text-xl">"Where did I stop yesterday? Was it Ayah 45 or 54?"</p>
                                </li>
                                <li class="flex items-start gap-4">
                                    <div class="h-6 w-6 rounded-full bg-white/20 flex items-center justify-center shrink-0 mt-1">✕</div>
                                    <p class="text-xl">"I've been so busy, I haven't opened my Mushaf in 3 days."</p>
                                </li>
                                <li class="flex items-start gap-4">
                                    <div class="h-6 w-6 rounded-full bg-white/20 flex items-center justify-center shrink-0 mt-1">✕</div>
                                    <p class="text-xl">"I want to finish my Khatm, but I lack a simple system."</p>
                                </li>
                            </ul>
                        </div>
                        <div class="bg-white/5 p-12 rounded-[3rem] border border-white/10 backdrop-blur-sm">
                            <h3 class="text-3xl font-bold mb-4 italic">AyaahReminder is the solution.</h3>
                            <p class="text-xl opacity-90 leading-relaxed">
                                We designed this app to remove every bit of friction between you and the Qur'an. By automating the "where am I?" and the "don't forget," we help you build a relationship that lasts a lifetime.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Features Showcase -->
            <section id="features" class="py-24 lg:py-40">
                <div class="container mx-auto px-4 lg:px-8">
                    <div class="text-center mb-24">
                        <h2 class="text-4xl font-extrabold tracking-tight sm:text-6xl mb-6">Designed for Your Journey</h2>
                        <p class="text-muted-foreground text-xl max-w-2xl mx-auto leading-relaxed">Simple features that make a massive difference in your spiritual consistency.</p>
                    </div>
                    
                    <div class="grid gap-8 lg:grid-cols-3">
                        <Card title="Instant Tracking" class="border-none shadow-none bg-muted/30 p-12 rounded-[2.5rem]">
                            <div class="space-y-4">
                                <div class="h-16 w-16 bg-primary rounded-2xl flex items-center justify-center text-primary-foreground mb-8">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1 0-5H20" /></svg>
                                </div>
                                <h3 class="text-3xl font-bold">Never Forget</h3>
                                <p class="text-muted-foreground text-lg leading-relaxed">A digital bookmark that lives in your pocket. Record your progress in seconds, anywhere you are.</p>
                            </div>
                        </Card>
                        <Card title="Email Reminders" class="border-none shadow-none bg-muted/30 p-12 rounded-[2.5rem]">
                            <div class="space-y-4">
                                <div class="h-16 w-16 bg-primary rounded-2xl flex items-center justify-center text-primary-foreground mb-8">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                                </div>
                                <h3 class="text-3xl font-bold">Gentle Nudges</h3>
                                <p class="text-muted-foreground text-lg leading-relaxed">Life is loud. Let us remind you at your best time to sit with the word of Allah and find your peace.</p>
                            </div>
                        </Card>
                        <Card title="Reading History" class="border-none shadow-none bg-muted/30 p-12 rounded-[2.5rem]">
                            <div class="space-y-4">
                                <div class="h-16 w-16 bg-primary rounded-2xl flex items-center justify-center text-primary-foreground mb-8">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 8v4l3 3"/><circle cx="12" cy="12" r="10"/></svg>
                                </div>
                                <h3 class="text-3xl font-bold">See Progress</h3>
                                <p class="text-muted-foreground text-lg leading-relaxed">Visualize your consistency. Watch your history grow as you complete Surah after Surah.</p>
                            </div>
                        </Card>
                    </div>
                </div>
            </section>

            <!-- Testimonials -->
            <section class="py-24 bg-muted/20">
                <div class="container mx-auto px-4 lg:px-8">
                    <h2 class="text-3xl font-bold text-center mb-16 italic">"This app changed my morning routine."</h2>
                    <div class="grid md:grid-cols-3 gap-8">
                        <div v-for="t in testimonials" :key="t.name" class="p-8 bg-background rounded-3xl shadow-sm border">
                            <p class="text-lg mb-6 text-muted-foreground">"{{ t.quote }}"</p>
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-full bg-primary/10 flex items-center justify-center font-bold text-primary">
                                    {{ t.name[0] }}
                                </div>
                                <div>
                                    <p class="font-bold">{{ t.name }}</p>
                                    <p class="text-xs text-muted-foreground uppercase tracking-widest font-semibold">{{ t.role }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- FAQ Section -->
            <section class="py-24 lg:py-40">
                <div class="container mx-auto px-4 lg:px-8 max-w-4xl">
                    <h2 class="text-4xl font-extrabold tracking-tight sm:text-6xl mb-12 text-center">Frequently Asked</h2>
                    <div class="space-y-6">
                        <div v-for="faq in faqs" :key="faq.question" class="p-8 rounded-3xl border bg-card">
                            <h3 class="text-xl font-bold mb-3">{{ faq.question }}</h3>
                            <p class="text-muted-foreground leading-relaxed">{{ faq.answer }}</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Final Call to Action -->
            <section class="container mx-auto px-4 py-24 lg:pb-40">
                <div class="relative overflow-hidden rounded-[4rem] bg-primary px-8 py-24 text-primary-foreground text-center shadow-2xl">
                    <div class="relative z-10 flex flex-col items-center gap-8">
                        <h2 class="text-5xl font-extrabold sm:text-7xl max-w-3xl leading-tight">Start building your Qur'an habit today.</h2>
                        <p class="text-primary-foreground/80 text-xl sm:text-2xl max-w-xl">Join the Ummah in staying consistent. Completely free.</p>
                        <Link
                            v-if="canRegister && !$page.props.auth.user"
                            :href="register()"
                            class="inline-flex h-20 items-center justify-center rounded-full bg-background px-16 py-6 text-2xl font-bold text-primary shadow-xl transition-all hover:scale-105 active:scale-95"
                        >
                            Get Started Now
                        </Link>
                        <Link
                            v-if="$page.props.auth.user"
                            :href="dashboard()"
                            class="inline-flex h-20 items-center justify-center rounded-full bg-background px-16 py-6 text-2xl font-bold text-primary shadow-xl transition-all hover:scale-105 active:scale-95"
                        >
                            Open Dashboard
                        </Link>
                    </div>
                </div>
            </section>
        </main>

        <footer class="border-t py-20 bg-muted/30">
            <div class="container mx-auto flex flex-col items-center justify-between gap-12 px-4 lg:flex-row lg:px-8">
                <div class="flex flex-col items-center lg:items-start gap-4">
                    <AppLogo />
                    <p class="text-sm text-muted-foreground max-w-xs text-center lg:text-left leading-relaxed">
                        Helping the Ummah build meaningful habits through simple, focused technology.
                    </p>
                </div>
                <div class="flex flex-col items-center lg:items-end gap-6">
                    <div class="flex gap-10 text-sm font-bold text-muted-foreground">
                        <a href="https://twitter.com/sahrialardians" target="_blank" class="hover:text-primary transition-colors uppercase tracking-widest">Founder</a>
                        <a href="#" class="hover:text-primary transition-colors uppercase tracking-widest">Privacy</a>
                        <a href="#" class="hover:text-primary transition-colors uppercase tracking-widest">Terms</a>
                    </div>
                    <p class="text-xs text-muted-foreground font-medium italic">© 2026 AyaahReminder. Al-Hamdulillah.</p>
                </div>
            </div>
        </footer>
    </div>
</template>
