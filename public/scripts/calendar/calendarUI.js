class CalendarUI {
    constructor() {
        this.calendar = new Calendar()
        this.weekDays = this.calendar.getWeekDays();
        this.MonthNames = this.calendar.getMonth();
        this.calendarDays = this.calendar.getCalendarDays();
        this.weekdays = this.calendar.getWeekDays()
        this.header=$('.day-header')
        this.loader = $('.loader-wrapper')
        this.calendarEl =$('.calendar')
        this.prevMonthBtn = $('#prevMonthButton')
        this.nextMonthBtn = $('#nextMonthButton')
        this.monthYearDisplay=$('#monthYearDisplay')
        this.calendarContent = $("#bazi-calendar");
        this.heavenlyStems = ["Jia", "Yi", "Bing", "Ding", "Wu", "Ji", "Geng", "Xin", "Ren", "Gui"];
        this.earthlyBranches = ["Zi (Rat)", "Chou (Ox)", "Yin (Tiger)", "Mao (Rabbit)", "Chen (Dragon)",
            "Si (Snake)", "Wu (Horse)", "Wei (Goat)", "Shen (Monkey)", "You (Rooster)",
            "Xu (Dog)", "Hai (Pig)"];
        this.currentMonth = this.calendarDays.find(({date})=> date.isToday)
        this.today= `${this.currentMonth.date.cn.month} ${this.MonthNames[this.currentMonth.date.month]}`

        // Precompile Handlebars templates
        this.dayCellTemplate = Handlebars.compile($('#day_cell').html());
        this.leftBarTemplate = Handlebars.compile($('#entry-template').html());
        this.dynamicDateTableTemplate = Handlebars.compile($('#dynamic_date').html());
        this.hourlyDetailsTemplate = Handlebars.compile($('#hourly').html());

    }
    async init(){
        this.prevMonthBtn.on('click', this.prevMonth.bind(this));
        this.nextMonthBtn.on('click', this.nextMonth.bind(this));
        this.showLoader()
        const res =await fetch('/check_sub')
        const data = await res.json()
        this.subscription = data?.user_subscription

        await this.renderUI()
        await this.hideLoader()

    }
    async renderUI(){
        const today = new Date();
        this.calendarDays = this.calendar.getCalendarDays();
        this.currentMonth = this.calendarDays.find(({date})=> date.isToday)
        if (this.currentMonth){
        this.todayDetails = this.calendar.getDayDetails(this.calendarDays.indexOf(this.currentMonth));
        }else{
            this.todayDetails = this.calendar.getDayDetails(this.calendarDays.indexOf(this.calendar.getCurrentMonth()));
        }
        await this.renderLeftBar()
        if(this.subscription){
            await this.updateDynamicDetails(); // Update the additional details section
            if(premiumPlans.includes(this.subscription?.membership_plan_id)){
                await this.updateHourlyDetails(this.todayDetails);
                await this.renderBaZiList(today);
                await this.renderDailyStars(today);
            }
        }
        await this.render();
    }
    hideLoader(){
        this.loader.hide()
    }
    showLoader(){
        this.loader.show()
    }
    prevMonth(){
        this.calendar.previousMonth()
        this.renderUI()
    }
    nextMonth(){
        this.calendar.nextMonth()
        this.renderUI()
    }

    Events(){
        document.getElementById("bazi-calendar").addEventListener("click", (event) => {
            if (event.target.closest(".calendar-cell")) {
                const cell = event.target.closest(".calendar-cell");
                const day = parseInt(cell.querySelector(".date-number").textContent);
                let month = parseInt(document.getElementById("month-picker").value);
                let year = parseInt(document.getElementById("year-picker").value);

                if (cell.classList.contains("prev-month")) {
                    month -= 1;
                    if (month < 0) {
                        month = 11;
                        year -= 1;
                    }
                } else if (cell.classList.contains("next-month")) {
                    month += 1;
                    if (month > 11) {
                        month = 0;
                        year += 1;
                    }
                }

                // const selectedDate = new Date(year, month, day);
                // updateLeftPanel(selectedDate);
                // updateDynamicDetails(selectedDate);
                // updateHourlyDetails(selectedDate);
                // renderBaZiList(selectedDate);
                // renderDailyStars(selectedDate);
            }
        });
    }

    renderHeader(){
        this.header.html(this.weekdays.map(weekday => `<div class="day-box">${weekday}</div>`).join(''))
    }

    render() {
        // Update month and year title
        this.calendarDays = this.calendar.getCalendarDays();
        this.currentMonth = this.calendarDays.find(({date})=> date.isToday) || this.calendar.getCurrentMonth()
        const month_number = this.currentMonth?.date?.month ?? this.currentMonth?.monthNumber
        const month_cn =this.currentMonth.date?.cn?.month ?? this.currentMonth?.cn?.month
        this.today= `${month_cn} ${this.MonthNames[month_number]} - ${this.currentMonth.date.year ?? this.currentMonth.year}`
        this.monthYearDisplay.text(this.today);

        const dayCell = $('#day_cell');
        const dayCellTemplate = dayCell.html();
        this.calendarContent.html(''); // Clear the calendar before populating
        const renderedDays =this.calendarDays.map((day, idx) => {
            // Prepare the data object for Mustache rendering
            let data = {
                idx: idx,
                class:day.date.isCurrentMonth ?day.rating.class :'prev-month',
                day_number: day.date.dayOfMonth,
                stem: day.lunar.stem,
                branch: day.lunar.branch,
                stem_en: day.lunar.english.stem,
                branch_en: day.lunar.english.branch,
                officer: day.lunar.officer.full,
            };

            // Render the template with data
            return this.dayCellTemplate(data);
            // Append the manipulated cell back to the DOM
        });
        this.calendarContent.append(renderedDays);

        this.renderLeftBar(this.todayDetails);
    }

    renderLeftBar(todayDetails){
         const today = todayDetails || this.calendar.todayDetails
        const dayDetailsTemplate = $('#entry-template').html()
        var context = {
            day:today.date.dayOfMonth,
            month:this.MonthNames[today.date.month],
            year:today.date.year,
            dongGongRating:today.rating.type,
            officer:today.lunar.officer.full,
            clashOne:today.clashes.clash_one.cnName,//[today.clashes.clash_one.stem,today.clashes.clash_one.branch],
            clashOneEnglish:today.clashes.clash_one.enName,//`${today.clashes.clash_one.stem_en} ${today.clashes.clash_one.branch_en}`,
            dayClash:today.clashes.day.cnName,//[today.clashes.day.stem ,today.clashes.day.branch],
            dayClashEnglish:today.clashes.day.enName,//`${today.clashes.day.stem_en} ${today.clashes.day.branch_en}`,
            clashTwo:today.clashes.clash_two.cnName,//[today.clashes.clash_two.stem,today.clashes.clash_two.branch],
            clashTwoEnglish:today.clashes.clash_two.enName,//`${today.clashes.clash_two.stem_en} ${today.clashes.clash_two.branch_en}`,
            GodOfWealth:today.positions.cai.description,
            GodOfLuck:today.positions.xi.description,
            GodOfFortune:today.positions.fu.description,
            YangNoble:today.positions.yangGui.description,
            YinNoble:today.positions.yinGui.description,
            user_subscription:userSubscription !== 'null'

        };
        // Pass the subscription status to the context for Handlebars
        context.user_subscription = this.subscription ? true : false;
        const finalTemplate = this.leftBarTemplate(context)

        $('#day_details').html(finalTemplate)
    }
    updateDynamicDetails(data) {
        const today = data || this.calendar.todayDetails
        const {date , lunar: lunarDay, clashes, positions, rating} = today // Renamed lunar to lunarDay for clarity

        // Example data (replace with actual logic or data from the server)
        const details = {
            fullDate:date.full,
            dongGong:lunarDay.officer.full,
            pillar: `${lunarDay.stem} ${lunarDay.branch}`, // Changed order
            action: lunarDay.officer.english, // Populated action field
            conflict: `${clashes.clash_two.branch} ${ clashes.clash_two.branch_en }`,
            yearBranch: `${lunarDay.year.branch} ${chineseToEnglishMapping.earthlyBranches[lunarDay.year.branch]}`,
            dayBranch: `${lunarDay.branch} ${chineseToEnglishMapping.earthlyBranches[lunarDay.branch]}`,
            dayStem: `${lunarDay.stem} ${chineseToEnglishMapping.heavenlyStems[lunarDay.stem]}`,
            recommendedActions: [], // Initialize as empty, will be populated below
            // Static auxiliaryStars as a fallback, will be replaced if dynamic data is found
            auxiliaryStars: [
                { icon: "🐉", name: "Dragon Virtue", branch:["Ox"]  },
                { icon: "⭐", name: "General Star", branch: ["Snake"] },
                { icon: "🐴", name: "Sky Horse", branch: ["Horse", "Dog", "Tiger"] },
                { icon: "🌟", name: "Five Prosperity", branch: ["Rooster", "Ox", "Snake"] },
                { icon: "💰", name: "Golden Lock", branch: ["Goat"] },
                { icon: "☀️", name: "Sun", branch: ["Goat"] },
                { icon: "🌙", name: "Moon", branch: ["Snake"] },
                { icon: "❤️", name: "Sky Happiness", branch: ["Ox"] },
                { icon: "🌀", name: "Day Combo", branch: ["Snake"] },
                { icon: "⚔️", name: "Day Punishment", branch: ["Snake"] },
                { icon: "✂️", name: "Separating Edge", branch: ["Horse"] },
                { icon: "❌", name: "Day Breaker", branch: ["Tiger"] },
                { icon: "🛒", name: "Great Consumer", branch: ["Tiger"] },
                { icon: "⚖️", name: "Litigation", branch: ["Dragon"] },
                { icon: "👻", name: "Five Ghosts", branch: ["Dragon"] },
                { icon: "⚕️", name: "Sickness Charm", branch: ["Rooster"] },
                { icon: "🪓", name: "Robbery Sha", branch: ["Rabbit", "Goat", "Pig"] },
                { icon: "🔥", name: "Calamity Sha", branch: ["Pig", "Goat"] },
                { icon: "🚨", name: "Disaster Sha", branch: ["Snake"] },
                { icon: "👔", name: "Heavenly Nobleman", branch:[],stem: ["Yin Wood", "Yin Earth"] },
                { icon: "💼", name: "Heavenly Wealth", branch:[], stem: ["Yang Water"] },
                { icon: "🚗", name: "Golden Carriage", branch:[],stem: ["Yang Fire", "Yin Earth"] },
                { icon: "📘", name: "Intelligence", branch: [] ,stem:["Yang Fire", "Yang Earth"] },
            ]
        };

        // --- Recommended Actions Yi/Ji ---
        let yiJiSourced = false;
        if (lunarDay) {
            if (typeof lunarDay.getYi === 'function' && typeof lunarDay.getJi === 'function') {
                const yiActions = lunarDay.getYi();
                const jiActions = lunarDay.getJi();

                if (Array.isArray(yiActions) && yiActions.length > 0) {
                    yiActions.forEach(action => details.recommendedActions.push(`Suitable for: ${action}`));
                    yiJiSourced = true;
                }
                if (Array.isArray(jiActions) && jiActions.length > 0) {
                    jiActions.forEach(action => details.recommendedActions.push(`Unsuitable for: ${action}`));
                    yiJiSourced = true;
                }
                if (!yiJiSourced && (Array.isArray(yiActions) || Array.isArray(jiActions))) {
                    // Methods exist but returned empty arrays
                     console.log("DynamicDetails: lunarDay.getYi() or lunarDay.getJi() returned empty arrays.");
                }
            } else {
                console.log("DynamicDetails: lunarDay.getYi() or lunarDay.getJi() methods not available.");
            }
        } else {
            console.error("DynamicDetails: lunarDay object is not available for Yi/Ji recommendations.");
        }

        // Fallback to officer/rating recommendations if Yi/Ji not sourced
        if (!yiJiSourced) {
            if (lunarDay.officer.english === 'Establish') {
                details.recommendedActions.push("Good day for starting new ventures.");
            }
            if (lunarDay.officer.english === 'Full') {
                details.recommendedActions.push("Activities related to abundance are favored.");
            }
            if (lunarDay.officer.english === 'Remove') {
                details.recommendedActions.push("Suitable for clearing out old things or ending chapters.");
            }
            if (rating && rating.type === 'Bad') {
                details.recommendedActions.push("Exercise caution in important activities.");
            }
            if (rating && rating.type === 'Excellent') {
                details.recommendedActions.push("An auspicious day for most activities.");
            }
        }

        // If no recommendations from any source, add a general one
        if (details.recommendedActions.length === 0) {
            details.recommendedActions.push("Consider the day's general influences for your activities.");
        }

        // --- Auxiliary Stars ---
        let newAuxiliaryStars = [];
        let dynamicAuxStarsAdded = false;
        let auxStarLog = "";

        if (lunarDay) {
            // Try getDayShenSha()
            if (typeof lunarDay.getDayShenSha === 'function') {
                const dayShenSha = lunarDay.getDayShenSha();
                if (Array.isArray(dayShenSha)) {
                    dayShenSha.forEach(star => {
                        if (typeof star === 'object' && star.name) {
                            let icon = '❓';
                            if (star.type === 'good' || star.type === '吉' || star.lucky === true) icon = '吉';
                            else if (star.type === 'bad' || star.type === '凶' || star.lucky === false) icon = '凶';
                            newAuxiliaryStars.push({ icon: icon, name: star.name, branch: [] });
                            dynamicAuxStarsAdded = true;
                        }
                    });
                    if (dayShenSha.length === 0) auxStarLog += "getDayShenSha() returned empty array. ";
                } else {
                     auxStarLog += "getDayShenSha() did not return an array. ";
                }
            } else {
                 auxStarLog += "getDayShenSha() not found. ";
            }

            // Try getXiu() and getXiuLuck()
            if (typeof lunarDay.getXiu === 'function' && typeof lunarDay.getXiuLuck === 'function') {
                const xiuName = lunarDay.getXiu();
                const xiuLuck = lunarDay.getXiuLuck(); // Expected '吉' or '凶'
                if (xiuName && typeof xiuLuck === 'string') {
                    let icon = '❓';
                    if (xiuLuck === '吉') icon = '吉';
                    else if (xiuLuck === '凶') icon = '凶';
                    newAuxiliaryStars.push({ icon: icon, name: `宿: ${xiuName} (${xiuLuck})`, branch: [] });
                    dynamicAuxStarsAdded = true;
                } else {
                    auxStarLog += "getXiu() or getXiuLuck() did not return expected data. ";
                }
            } else {
                 auxStarLog += "getXiu()/getXiuLuck() not found. ";
            }

            // Try getPengZuBaiJi()
            if (typeof lunarDay.getPengZuBaiJi === 'function') {
                const pengZu = lunarDay.getPengZuBaiJi();
                if (Array.isArray(pengZu)) {
                    pengZu.forEach(taboo => {
                        newAuxiliaryStars.push({ icon: '忌', name: taboo, branch: [] });
                        dynamicAuxStarsAdded = true;
                    });
                     if (pengZu.length === 0) auxStarLog += "getPengZuBaiJi() returned empty array. ";
                } else {
                    auxStarLog += "getPengZuBaiJi() did not return an array. ";
                }
            } else {
                auxStarLog += "getPengZuBaiJi() not found. ";
            }

        } else {
            auxStarLog = "lunarDay object not available for auxiliary stars. ";
        }

        if (dynamicAuxStarsAdded) {
            details.auxiliaryStars = newAuxiliaryStars;
            if (newAuxiliaryStars.length === 0) { // Methods existed but all returned empty
                 console.log("DynamicDetails: Auxiliary star methods available but returned no stars. " + auxStarLog);
            }
        } else {
            // Keep static auxiliaryStars and log the attempt/failure
            console.log("DynamicDetails: Could not dynamically populate auxiliary stars. Using static fallback. Attempts: " + auxStarLog);
        }

        // Construct HTML for the details section
        // const template = $("#dynamic_date").html(); // Source is already compiled
        const html = this.dynamicDateTableTemplate({ details });
        // Inject the generated HTML into the details section
        $("#dynamic-details").html(html);
    }

    updateHourlyDetails(data) {
        const today = data || this.calendar.todayDetails;
        const { hourly } = today.lunar; // This is an array of 'time' objects from Lunar.js

        let overallActivitySourced = false; // Tracks if any hour got activity data
        let overallDetailsSourced = false; // Tracks if any hour got detail data

        const processedHourlyDetails = hourly.map(time => {
            const hourData = {
                timeRange: `${time.getMinHm()} - ${time.getMaxHm()}`,
                stemBranch: time.getGanZhi(),
                stemBranchEn: `${chineseToEnglishMapping.heavenlyStems[time.getGan()]} ${chineseToEnglishMapping.earthlyBranches[time.getZhi()]}`,
                activity: "Varies",
                details: []
            };

            let activitySourcedThisHour = false;
            let detailsSourcedThisHour = false;
            let tianShenName = null; // To store TianShen name for details if found

            // 1. Hourly Activity
            if (typeof time.getTianShen === 'function') {
                const tianShen = time.getTianShen(); // e.g., {name: '青龙', type: '吉'} or '青龙'
                if (tianShen) {
                    if (typeof tianShen === 'object' && tianShen.name) {
                        tianShenName = tianShen.name;
                        let luckType = tianShen.type || tianShen.luck; // '吉', '凶', 'good', 'bad'
                        if (luckType && (luckType.includes('吉') || luckType.toLowerCase().includes('good'))) {
                            hourData.activity = `Auspicious (${tianShenName})`;
                        } else if (luckType && (luckType.includes('凶') || luckType.toLowerCase().includes('bad'))) {
                            hourData.activity = `Inauspicious (${tianShenName})`;
                        } else {
                            hourData.activity = tianShenName; // Neutral or just name
                        }
                        activitySourcedThisHour = true;
                    } else if (typeof tianShen === 'string') {
                        tianShenName = tianShen;
                        hourData.activity = tianShenName; // Just the name, actual luck unknown from this
                        activitySourcedThisHour = true;
                    }
                }
            }

            if (!activitySourcedThisHour && typeof time.getHuangDaoJiXiong === 'function') {
                const luck = time.getHuangDaoJiXiong(); // Expected '吉' or '凶'
                if (luck === '吉') {
                    hourData.activity = "Yellow Path - Auspicious";
                    activitySourcedThisHour = true;
                } else if (luck === '凶') {
                    hourData.activity = "Black Path - Inauspicious";
                    activitySourcedThisHour = true;
                }
            }

            if (!activitySourcedThisHour && typeof time.getShiErShen === 'function') { // 12 Day Officers / Jian Chu
                const shiErShen = time.getShiErShen(); // e.g., {name: '建', type: '吉'} or just '建'
                 if (shiErShen) {
                    if (typeof shiErShen === 'object' && shiErShen.name) {
                        let luckPrefix = "";
                        if (shiErShen.type === '吉' || shiErShen.type === 'good') luckPrefix = "Auspicious - ";
                        else if (shiErShen.type === '凶' || shiErShen.type === 'bad') luckPrefix = "Inauspicious - ";
                        hourData.activity = `${luckPrefix}${shiErShen.name}`;
                        activitySourcedThisHour = true;
                    } else if (typeof shiErShen === 'string') {
                        hourData.activity = shiErShen; // e.g. "建 (Establish)"
                        activitySourcedThisHour = true;
                    }
                }
            }
            if(activitySourcedThisHour) overallActivitySourced = true;


            // 2. Hourly Details
            if (tianShenName) { // From getTianShen() attempt above
                hourData.details.push(tianShenName);
                detailsSourcedThisHour = true;
            }

            if (typeof time.getHourShenSha === 'function') {
                const hourShenSha = time.getHourShenSha(); // Expected array of strings or objects
                if (Array.isArray(hourShenSha)) {
                    hourShenSha.forEach(sha => {
                        if (typeof sha === 'string') hourData.details.push(sha);
                        else if (typeof sha === 'object' && sha.name) hourData.details.push(sha.name);
                    });
                    if (hourShenSha.length > 0) detailsSourcedThisHour = true;
                }
            }

            if (typeof time.getPositionShen === 'function') {
                 const posShen = time.getPositionShen(); // Could be a string or an object
                 if(posShen){
                    if(typeof posShen === 'string') hourData.details.push(posShen);
                    else if (posShen.name) hourData.details.push(posShen.name);
                    detailsSourcedThisHour = true;
                 }
            }

            if (typeof time.getChong === 'function') {
                const chong = time.getChong(); // e.g., "卯"
                if (chong) {
                    hourData.details.push(`Clash: ${chong} (${chineseToEnglishMapping.earthlyBranches[chong] || ''})`);
                    detailsSourcedThisHour = true;
                }
            }
            if (typeof time.getXing === 'function') {
                const xing = time.getXing(); // e.g., "子卯刑" or just a branch
                if (xing) {
                    hourData.details.push(`Punishment: ${xing}`);
                    detailsSourcedThisHour = true;
                }
            }
            if(detailsSourcedThisHour) overallDetailsSourced = true;

            // Remove duplicates from details if any were added from multiple sources
            if (hourData.details.length > 0) {
                hourData.details = [...new Set(hourData.details)];
            }

            return hourData;
        });

        if (!overallActivitySourced) {
            console.log("updateHourlyDetails: Could not dynamically source 'activity' for hourly cards. Defaulting to 'Varies'. Review available methods on the Lunar.js Time object (e.g., getTianShen, getHuangDaoJiXiong, getShiErShen).");
        }
        if (!overallDetailsSourced) {
            console.log("updateHourlyDetails: Could not dynamically source nested 'details' for hourly cards. These will be empty or sparsely populated. Review available methods on the Lunar.js Time object (e.g., getHourShenSha, getPositionShen, getChong, getXing).");
        }

        const template = $("#hourly").html();
        const html = Mustache.render(template, { hourly_details: processedHourlyDetails });
        $("#hourly-details-grid").html(html);
    }

    renderBaZiList() { // Removed 'date' parameter, will use this.todayDetails
        const dayData = this.todayDetails;
        const pillarsData = [];

        if (!dayData || !dayData.lunar) {
            console.error("renderBaZiList: todayDetails or todayDetails.lunar is not available.");
            // Optionally, clear the container or display a message
            const container = document.getElementById("bazi-list");
            if (container) container.innerHTML = "<p>BaZi data unavailable.</p>";
            return;
        }

        const lunar = dayData.lunar;

        // Year Pillar
        if (lunar.year && lunar.year.stem && lunar.year.branch) {
            pillarsData.push({
                title: "Year Pillar",
                stemBranch: `${lunar.year.stem}${lunar.year.branch}`,
                english: `${chineseToEnglishMapping.heavenlyStems[lunar.year.stem]} ${chineseToEnglishMapping.earthlyBranches[lunar.year.branch]}`
            });
        } else {
            console.log("renderBaZiList: Year pillar data is missing.");
            pillarsData.push({ title: "Year Pillar", stemBranch: "N/A", english: "Data unavailable" });
        }

        // Month Pillar
        if (lunar.month && lunar.month.stem && lunar.month.branch) {
            pillarsData.push({
                title: "Month Pillar",
                stemBranch: `${lunar.month.stem}${lunar.month.branch}`,
                english: `${chineseToEnglishMapping.heavenlyStems[lunar.month.stem]} ${chineseToEnglishMapping.earthlyBranches[lunar.month.branch]}`
            });
        } else {
            console.log("renderBaZiList: Month pillar data is missing.");
            pillarsData.push({ title: "Month Pillar", stemBranch: "N/A", english: "Data unavailable" });
        }

        // Day Pillar
        if (lunar.stem && lunar.branch) {
            pillarsData.push({
                title: "Day Pillar",
                stemBranch: `${lunar.stem}${lunar.branch}`,
                english: `${chineseToEnglishMapping.heavenlyStems[lunar.stem]} ${chineseToEnglishMapping.earthlyBranches[lunar.branch]}`,
                highlight: true // Highlight the Day Pillar
            });
        } else {
            console.log("renderBaZiList: Day pillar data is missing.");
            pillarsData.push({ title: "Day Pillar", stemBranch: "N/A", english: "Data unavailable", highlight: true });
        }

        // Hour Pillar (First hour of the day - Zi hour)
        if (lunar.hourly && lunar.hourly.length > 0 && lunar.hourly[0]) {
            const firstHour = lunar.hourly[0];
            const hourStem = firstHour.getGan();
            const hourBranch = firstHour.getZhi();
            if (hourStem && hourBranch) {
                pillarsData.push({
                    title: `Hour Pillar (${hourBranch})`, // e.g., Hour Pillar (子)
                    stemBranch: `${hourStem}${hourBranch}`,
                    english: `${chineseToEnglishMapping.heavenlyStems[hourStem]} ${chineseToEnglishMapping.earthlyBranches[hourBranch]}`
                });
            } else {
                console.log("renderBaZiList: First hour pillar data (stem/branch) is missing.");
                pillarsData.push({ title: "Hour Pillar", stemBranch: "N/A", english: "Data unavailable" });
            }
        } else {
            console.log("renderBaZiList: Hourly data for the first hour is missing.");
            pillarsData.push({ title: "Hour Pillar", stemBranch: "N/A", english: "Data unavailable" });
        }

        const container = document.getElementById("bazi-list");
        container.innerHTML = ""; // Clear existing content

        pillarsData.forEach((item) => {
            const itemDiv = document.createElement("div");
            itemDiv.classList.add("bazi-item");
            if (item.highlight) {
                itemDiv.classList.add("highlight");
            }

            const titleEl = document.createElement("h3");
            titleEl.textContent = item.title;
            itemDiv.appendChild(titleEl);

            const stemBranchEl = document.createElement("div");
            stemBranchEl.classList.add("bazi-info");
            // Display Chinese stemBranch and English translation side-by-side or one above the other
            stemBranchEl.innerHTML = `<span>${item.stemBranch}</span><span style="font-size: smaller; color: grey; margin-left: 5px;">(${item.english})</span>`;
            itemDiv.appendChild(stemBranchEl);

            // No 'details' list for pillars as per requirements

            container.appendChild(itemDiv);
        });
    }

    renderDailyStars() { // Removed date parameter, will use this.todayDetails
        const dayData = this.todayDetails;
        let dynamicDailyStarsData = {
            date: "N/A",
            details: []
        };

        if (!dayData || !dayData.lunar || !dayData.date || !dayData.positions) {
            console.error("renderDailyStars: Essential data (todayDetails, lunar, date, positions) is not available.");
            const container = document.getElementById("daily-stars");
            if (container) container.innerHTML = "<p>Daily stars data unavailable.</p>";
            return;
        }

        dynamicDailyStarsData.date = dayData.date.full;

        // 1. Daily Stars, Auspicious and Inauspicious Directions
        const directionalStarsItems = [];
        const positions = dayData.positions;
        const positionMapping = [
            { key: 'cai', name: 'God of Wealth' },
            { key: 'xi', name: 'God of Luck' },
            { key: 'fu', name: 'God of Fortune' },
            { key: 'yangGui', name: 'Yang Nobleman' },
            { key: 'yinGui', name: 'Yin Nobleman' }
        ];

        positionMapping.forEach(posMap => {
            if (positions[posMap.key] && positions[posMap.key].description) {
                directionalStarsItems.push({
                    star: posMap.name,
                    direction: positions[posMap.key].description.english || "N/A",
                    comment: positions[posMap.key].description.chinese || positions[posMap.key].value || "N/A"
                });
            } else {
                console.log(`renderDailyStars: Data for directional star '${posMap.name}' is missing.`);
                directionalStarsItems.push({ star: posMap.name, direction: "N/A", comment: "Data unavailable" });
            }
        });

        // Attempt to find other directional stars from Lunar object (speculative)
        // For example, if d.getExtraDirections() existed and returned an array like [{name: "Tai Sui", direction: "NW", comment: "Year God"}]
        const lunarDay = dayData.lunar; // This is 'd' in calendar.js, renamed for clarity

        // Peng Zu Bai Ji - ensure this is part of the directional stars items
        if (typeof lunarDay.getPengZuBaiJi === 'function') {
            const pengZuTaboos = lunarDay.getPengZuBaiJi(); // Returns array of strings
            if (Array.isArray(pengZuTaboos) && pengZuTaboos.length > 0) {
                pengZuTaboos.forEach(taboo => {
                    directionalStarsItems.push({ star: "Peng Zu Taboo", direction: "Daily Advice", comment: taboo });
                });
            } else if (!Array.isArray(pengZuTaboos)) {
                 console.log("renderDailyStars: lunarDay.getPengZuBaiJi() did not return an array.");
            }
        } else {
            console.log("renderDailyStars: lunarDay.getPengZuBaiJi() method not found.");
        }

        // Fetal God - ensure this is part of the directional stars items
        if (typeof lunarDay.getFetalGodDay === 'function') {
            const fetalGodString = lunarDay.getFetalGodDay(); // e.g. "占门碓外东南"
            if (fetalGodString && typeof fetalGodString === 'string') {
                 // Basic parsing attempt, specific parsing might be needed depending on string format variability
                let directionComment = fetalGodString;
                let directionDetail = "Varies";
                // Example: "占门碓外东南" -> "门碓外东南" as direction, full string as comment
                const parts = fetalGodString.split(' '); // if it's space separated
                if (parts.length > 1) directionDetail = parts.slice(1).join(' ');
                else { // Try to extract from common patterns like "占[object] [direction]"
                    const match = fetalGodString.match(/占(.*?) (.*)/) || fetalGodString.match(/占(.*?)([东南西北内外]+)/);
                    if (match && match[2]) {
                         directionDetail = (match[1] ? match[1] + " " : "") + match[2];
                    } else {
                        directionDetail = fetalGodString.startsWith("占") ? fetalGodString.substring(1) : fetalGodString;
                    }
                }
                directionalStarsItems.push({ star: "Fetal God", direction: directionDetail, comment: fetalGodString });
            } else if (fetalGodString){
                 console.log("renderDailyStars: lunarDay.getFetalGodDay() did not return a string.");
            }
        } else {
            console.log("renderDailyStars: lunarDay.getFetalGodDay() method not found.");
        }

        dynamicDailyStarsData.details.push({
            category: "Daily Stars, Auspicious and Inauspicious Directions",
            items: directionalStarsItems
        });

        // 2. Daily Auxiliary Star
        let newAuspiciousItems = [];
        let newInauspiciousItems = [];
        let dynamicDailyAuxStarsAdded = false;
        let auxStarLog = "";


        if (lunarDay) {
            if (typeof lunarDay.getDayShenSha === 'function') {
                const allShenSha = lunarDay.getDayShenSha();
                if (Array.isArray(allShenSha) && allShenSha.length > 0) {
                    allShenSha.forEach(star => {
                        if (typeof star === 'object' && star.name && star.type) {
                            if (star.type.toLowerCase() === 'good' || star.type.toLowerCase() === 'auspicious' || star.type === '吉') {
                                newAuspiciousItems.push(star.name);
                            } else if (star.type.toLowerCase() === 'bad' || star.type.toLowerCase() === 'inauspicious' || star.type === '凶') {
                                newInauspiciousItems.push(star.name);
                            }
                        } else if (typeof star === 'string') { // some libs might return array of strings if type is implicit
                            newAuspiciousItems.push(star); // Default to auspicious if type is unknown
                        }
                    });
                    if (newAuspiciousItems.length > 0 || newInauspiciousItems.length > 0) dynamicDailyAuxStarsAdded = true;
                    else auxStarLog += "getDayShenSha() returned empty or unparsable array. ";
                } else if (!Array.isArray(allShenSha)) {
                     auxStarLog += "getDayShenSha() did not return an array. ";
                } else {
                    auxStarLog += "getDayShenSha() returned empty array. ";
                }
            } else {
                 auxStarLog += "getDayShenSha() method not found. ";
                 // Fallback to getGoodStars/getBadStars if getDayShenSha is not present
                if (typeof lunarDay.getGoodStars === 'function' && typeof lunarDay.getBadStars === 'function') {
                    const goodStars = lunarDay.getGoodStars();
                    const badStars = lunarDay.getBadStars();
                    if (Array.isArray(goodStars) && goodStars.length > 0) {
                        newAuspiciousItems = goodStars.map(s => (typeof s === 'object' && s.name) ? s.name : s);
                        dynamicDailyAuxStarsAdded = true;
                    } else if (!Array.isArray(goodStars)) auxStarLog += "getGoodStars() did not return an array. ";

                    if (Array.isArray(badStars) && badStars.length > 0) {
                        newInauspiciousItems = badStars.map(s => (typeof s === 'object' && s.name) ? s.name : s);
                        dynamicDailyAuxStarsAdded = true;
                    } else if (!Array.isArray(badStars)) auxStarLog += "getBadStars() did not return an array. ";

                    if (!dynamicDailyAuxStarsAdded && (goodStars || badStars)) auxStarLog += "getGoodStars/BadStars returned empty or unparsable. ";

                } else {
                    auxStarLog += "getGoodStars()/getBadStars() methods not found. ";
                }
            }

            // Constellation (Xiu)
            if (typeof lunarDay.getXiu === 'function' && typeof lunarDay.getXiuLuck === 'function') {
                const xiuName = lunarDay.getXiu();
                const xiuLuck = lunarDay.getXiuLuck(); // '吉' or '凶'
                if (xiuName && typeof xiuLuck === 'string') {
                    const xiuString = `宿: ${xiuName} (${xiuLuck})`;
                    if (xiuLuck === '吉') {
                        newAuspiciousItems.push(xiuString);
                    } else if (xiuLuck === '凶') {
                        newInauspiciousItems.push(xiuString);
                    } else { // Neutral or other
                        newAuspiciousItems.push(xiuString); // Default to auspicious list if not '凶'
                    }
                    dynamicDailyAuxStarsAdded = true;
                } else {
                    auxStarLog += "getXiu() or getXiuLuck() did not return expected data. ";
                }
            } else {
                auxStarLog += "getXiu()/getXiuLuck() methods not found. ";
            }
        } else {
             auxStarLog += "lunarDay object not available. ";
        }

        let finalAuspiciousItems = newAuspiciousItems;
        let finalInauspiciousItems = newInauspiciousItems;

        if (!dynamicDailyAuxStarsAdded || (newAuspiciousItems.length === 0 && newInauspiciousItems.length === 0)) {
            console.log(`renderDailyStars: Could not dynamically populate Daily Auxiliary Stars. Using static placeholders. Attempts: ${auxStarLog}`);
            // Fallback to static data as previously defined if no dynamic stars were added or if methods returned empty.
            finalAuspiciousItems = ["Static Moon", "Static Four Phase", "Static Removal God", "Static Heavenly Virtue", "Static Success God", "Static Heavenly Horse"];
            finalInauspiciousItems = ["Static War Female", "Static Flying Disaster", "Static Sha God", "Static Imprisonment", "Static Lesser Consumer"];
        }

        dynamicDailyStarsData.details.push({
            category: "Daily Auxiliary Star",
            items: finalAuspiciousItems,
            inauspiciousItems: finalInauspiciousItems
        });

        const container = document.getElementById("daily-stars");
        container.innerHTML = ""; // Clear existing content

        const dateHeader = document.createElement("h3");
        dateHeader.textContent = `Date: ${dynamicDailyStarsData.date}`; // Use dynamic date
        dateHeader.classList.add("date-header");
        container.appendChild(dateHeader); // Append header first

        // Loop through the details from the new dynamic data structure
        dynamicDailyStarsData.details.forEach((section) => {
            const sectionTitle = document.createElement("p");
            sectionTitle.innerHTML = `<strong>${section.category}</strong>`; // Category names are now from dynamicDailyStarsData
            sectionTitle.classList.add("section-title");
            container.appendChild(sectionTitle);

            // Create the list/table for stars and directions
            if (section.category === "Daily Stars, Auspicious and Inauspicious Directions") {
                const table = document.createElement("table");
                table.classList.add("table", "styled-table");

                const thead = document.createElement("thead");
                thead.innerHTML = `
                    <tr>
                        <th>Star</th>
                        <th>Direction</th>
                        <th>Comment</th>
                    </tr>
                `;
                table.appendChild(thead);

                const tbody = document.createElement("tbody");
                section.items.forEach((item) => {
                    const row = document.createElement("tr");
                    row.innerHTML = `
                        <td>${item.star}</td>
                        <td>${item.direction}</td>
                        <td>${item.comment}</td>
                    `;
                    tbody.appendChild(row);
                });
                table.appendChild(tbody);

                container.appendChild(table);
            }

            // Create the list for auxiliary stars
            if (section.category === "Daily Auxiliary Star") {
                const list = document.createElement("ul");
                list.classList.add("auxiliary-star-list");

                // Add auspicious stars
                section.items.forEach((star) => {
                    const listItem = document.createElement("li");
                    listItem.textContent = star;
                    listItem.classList.add("auspicious");
                    list.appendChild(listItem);
                });

                // Add inauspicious stars
                section.inauspiciousItems.forEach((star) => {
                    const listItem = document.createElement("li");
                    listItem.textContent = star;
                    listItem.classList.add("inauspicious");
                    list.appendChild(listItem);
                });

                container.appendChild(list);
            }
        });
    }

}

// Initialize with the current month and year
$(document).ready(function () {
    const UI = new CalendarUI()
    UI.init()

})
