const musicContainer = document.getElementById('music-container');
const playBtn = document.getElementById('play');
const prevBtn = document.getElementById('prev');
const nextBtn = document.getElementById('next');

const audio = document.getElementById('audio');
const progress = document.getElementById('progress');
const progressContainer = document.getElementById('progress-container');
const title = document.getElementById('title');
const cover = document.getElementById('cover');
const currTime = document.querySelector('#currTime');
const durTime = document.querySelector('#durTime');
const remainingTime = document.querySelector('#remainingTime');
const volumeSlider = document.getElementById('formControlRange');

audio.volume = volumeSlider.value;


// Initially load song details into DOM
loadSong(doc_title);

// Update song details
function loadSong(song) {
    title.innerText = song;

    audio.src = audio_file;
    cover.src = coverimg;
}

// Play song
function playSong() {
    musicContainer.classList.add('play');
    playBtn.querySelector('i.fas').classList.remove('fa-play');
    playBtn.querySelector('i.fas').classList.add('fa-pause');

    audio.play();
}

// Pause song
function pauseSong() {
    musicContainer.classList.remove('play');
    playBtn.querySelector('i.fas').classList.add('fa-play');
    playBtn.querySelector('i.fas').classList.remove('fa-pause');

    audio.pause();
}

// Previous song
// function prevSong() {
//     songIndex--;
//
//     if (songIndex < 0) {
//         songIndex = songs.length - 1;
//     }
//
//     loadSong(songs[songIndex]);
//
//     playSong();
// }

// Next song
// function nextSong() {
//     songIndex++;
//
//     if (songIndex > songs.length - 1) {
//         songIndex = 0;
//     }
//
//     loadSong(songs[songIndex]);
//
//     playSong();
// }

// Update progress bar
function updateProgress(e) {
    const { duration, currentTime } = e.srcElement;
    const progressPercent = (currentTime / duration) * 100;
    progress.style.width = `${progressPercent}%`;
}

// Set progress bar
function setProgress(e) {
    const width = this.clientWidth;
    const clickX = e.offsetX;
    const duration = audio.duration;

    audio.currentTime = (clickX / width) * duration;
}

//get duration & currentTime for Time of song
function DurTime(e) {
    const { duration, currentTime } = e.srcElement;

    // Get the formatted current time using Moment.js
    const currentMoment = moment.utc(currentTime * 1000); // Convert seconds to milliseconds
    const formattedCurrentTime = currentMoment.format('mm:ss');

    // Update the current time in the DOM
    currTime.innerHTML = formattedCurrentTime;

    // Get the formatted duration using Moment.js
    const durationMoment = moment.utc(duration * 1000); // Convert seconds to milliseconds
    const formattedDuration = durationMoment.format('mm:ss');

    // Update the duration in the DOM
    durTime.innerHTML = formattedDuration;

    // Calculate the remaining time
    const remainingMoment = moment.utc((duration - currentTime) * 1000); // Remaining time in milliseconds
    const formattedRemainingTime = remainingMoment.format('mm:ss');

    // Update the remaining time in the DOM
    remainingTime.innerHTML = formattedRemainingTime;
}


// Event listeners
playBtn.addEventListener('click', () => {
    const isPlaying = musicContainer.classList.contains('play');

    if (isPlaying) {
        pauseSong();
    } else {
        playSong();
    }
});

// Change song
// prevBtn.addEventListener('click', prevSong);
// nextBtn.addEventListener('click', nextSong);
audio.addEventListener('loadstart',(e)=>{
    const {duration,currentTime} = e.srcElement;
    console.log(duration,currentTime);
})
function formatDuration(seconds) {
    // If the duration exceeds an hour, use HH:mm:ss format
    if (seconds >= 3600) {
        return moment.utc(seconds * 1000).format('HH:mm:ss');
    }
    // If it's less than an hour, use mm:ss format
    else {
        return moment.utc(seconds * 1000).format('mm:ss');
    }
}
audio.addEventListener('loadedmetadata', () => {
    const duration = audio.duration;
    const formattedDuration = formatDuration(duration);
    console.log('Audio duration: ' + formattedDuration);
    durTime.innerHTML = remainingTime.innerHTML = formattedDuration;
});
// Time/song update
audio.addEventListener('timeupdate', updateProgress);

// Click on progress bar
progressContainer.addEventListener('click', setProgress);

// Song ends
// audio.addEventListener('ended', nextSong);
volumeSlider.addEventListener('input', (event) => {
    audio.volume = event.target.value;
});
// Time of song
audio.addEventListener('timeupdate',DurTime);