<script setup>
import { ref } from "vue";

const services = ref([
    {
        title: "Consulting",
        description:
            "Layanan ini menyediakan jasa konsultan manajemen sumber daya manusia untuk organisasi. Fokus utama adalah membantu organisasi dalam meningkatkan efektivitas pengelolaan SDM melalui pendekatan profesional dan strategis, termasuk penyusunan strategi organisasi, pengembangan sistem SDM, dan solusi manajemen yang relevan.",
        isFlipped: false,
        order: 1,
        icon: "📊",
    },
    {
        title: "Learning Center",
        description:
            "Melalui layanan ini, PT. Ara Nuansa Katumbiri menyelenggarakan berbagai program pelatihan dan pengembangan SDM.",
        isFlipped: false,
        order: 2,
        icon: "🎓",
    },
    {
        title: "Recruitment and Assessment",
        description:
            "Layanan ini bertujuan membantu organisasi dalam mencari, memilih, dan merekomendasikan individu-individu dengan bakat terbaik untuk dikembangkan lebih lanjut. Proses ini mencakup rekrutmen dan penilaian calon karyawan secara menyeluruh, baik untuk kebutuhan industri maupun pendidikan.",
        isFlipped: false,
        order: 3,
        icon: "👥",
    },
]);

const isShuffling = ref(false);

const shuffleCards = () => {
    if (isShuffling.value) return;

    isShuffling.value = true;

    const newOrder = [...services.value]
        .map((value) => ({ value, sort: Math.random() }))
        .sort((a, b) => a.sort - b.sort)
        .map(({ value }) => value);

    setTimeout(() => {
        services.value = newOrder;
        setTimeout(() => {
            isShuffling.value = false;
        }, 600);
    }, 600);
};

const flipCard = (index) => {
    if (!isShuffling.value) {
        services.value[index].isFlipped = !services.value[index].isFlipped;
    }
};
</script>

<template>
    <div class="services-container mx-auto px-4 py-8 max-w-6xl">
        <p
            class="text-center text-xl mb-8"
            data-aos="fade-up"
            data-aos-delay="1200"
        >
            Perusahaan kami menawarkan tiga layanan utama yang menjadi inti
            kompetensi dan kegiatan bisnisnya:
        </p>

        <div class="relative">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div
                    v-for="(service, index) in services"
                    :key="service.title"
                    class="card-wrapper"
                    :class="{ shuffling: isShuffling }"
                    :style="{ order: service.order }"
                    @click="flipCard(index)"
                    data-aos="zoom-in-up"
                    :data-aos-delay="1400 + index * 200"
                >
                    <div
                        class="card"
                        :class="{ 'is-flipped': service.isFlipped }"
                    >
                        <div class="card-face card-front">
                            <div
                                class="bg-white rounded-xl shadow-lg p-6 h-full border-2 border-transparent hover:border-[#CDC052] transition-all duration-300"
                            >
                                <div class="text-4xl mb-4">
                                    {{ service.icon }}
                                </div>
                                <h3
                                    class="text-xl font-bold text-[#5932EA] -mt-2 mb-4"
                                >
                                    {{ service.title }}
                                </h3>
                                <div
                                    class="text-gray-700 flex items-center justify-between"
                                >
                                    <span>Klik untuk melihat detail</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-face card-back">
                            <div
                                class="bg-gradient-to-br from-[#5932EA] to-[#4D62D7] text-white rounded-xl shadow-lg p-6 h-full"
                            >
                                <h3
                                    class="text-xl font-bold mb-4 flex items-center"
                                >
                                    {{ service.icon }}
                                    <span class="ml-2">{{
                                        service.title
                                    }}</span>
                                </h3>
                                <p class="text-sm leading-relaxed">
                                    {{ service.description }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.card-wrapper {
    perspective: 1000px;
    transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
}

.card-wrapper:hover {
    transform: translateY(-5px);
}

.card-wrapper.shuffling {
    animation: shuffle 0.8s cubic-bezier(0.4, 0, 0.2, 1);
}

.card {
    position: relative;
    width: 100%;
    height: 100%;
    min-height: 300px;
    cursor: pointer;
    transform-style: preserve-3d;
    transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
}

.card.is-flipped {
    transform: rotateY(180deg);
}

.card-face {
    position: absolute;
    width: 100%;
    height: 100%;
    backface-visibility: hidden;
    transform-style: preserve-3d;
    transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
}

.card-back {
    transform: rotateY(180deg);
}

@keyframes shuffle {
    0% {
        transform: translateX(0) scale(1) rotate(0);
    }
    25% {
        transform: translateX(-20px) scale(0.95) rotate(-5deg);
    }
    75% {
        transform: translateX(20px) scale(0.95) rotate(5deg);
    }
    100% {
        transform: translateX(0) scale(1) rotate(0);
    }
}

/* Hover effects */
.card-wrapper:hover .card-face {
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
}

@keyframes float {
    0% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-5px);
    }
    100% {
        transform: translateY(0px);
    }
}
</style>
