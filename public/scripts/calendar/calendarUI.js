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
            return Mustache.render(dayCellTemplate, data);
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
        const finalTemplate = Mustache.render(dayDetailsTemplate,context)

        $('#day_details').html(finalTemplate)
    }
    updateDynamicDetails(data) {
        const today = data || this.calendar.todayDetails
        const {date , lunar, clashes, positions, rating} = today // Added rating here

        // Example data (replace with actual logic or data from the server)
        const details = {
            fullDate:date.full,
            dongGong:lunar.officer.full,
            pillar: `${lunar.stem} ${lunar.branch}`, // Changed order
            action: lunar.officer.english, // Populated action field
            conflict: `${clashes.clash_two.branch} ${ clashes.clash_two.branch_en }`,
            yearBranch: `${lunar.year.branch} ${chineseToEnglishMapping.earthlyBranches[lunar.year.branch]}`,
            dayBranch: `${lunar.branch} ${chineseToEnglishMapping.earthlyBranches[lunar.branch]}`,
            dayStem: `${lunar.stem} ${chineseToEnglishMapping.heavenlyStems[lunar.stem]}`,
            recommendedActions: [], // Initialize as empty, will be populated below
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

        // Dynamic recommendations
        if (lunar.officer.english === 'Establish') {
            details.recommendedActions.push("Good day for starting new ventures.");
        }
        if (lunar.officer.english === 'Full') {
            details.recommendedActions.push("Activities related to abundance are favored.");
        }
        if (lunar.officer.english === 'Remove') {
            details.recommendedActions.push("Suitable for clearing out old things or ending chapters.");
        }
        if (rating && rating.type === 'Bad') {
            details.recommendedActions.push("Exercise caution in important activities.");
        }
        if (rating && rating.type === 'Excellent') {
            details.recommendedActions.push("An auspicious day for most activities.");
        }

        // If no specific recommendations, add a general one
        if (details.recommendedActions.length === 0) {
            details.recommendedActions.push("Consider the day's general influences for your activities.");
        }

        // Log for auxiliary stars
        console.log("DynamicDetails: Auxiliary stars data is currently static. Needs integration with a dynamic data source if available from the Lunar object or API.");

        // Construct HTML for the details section
        const template = $("#dynamic_date").html();
        const html = Mustache.render(template, { details });
        // Inject the generated HTML into the details section
        $("#dynamic-details").html(html);
    }

    updateHourlyDetails(data) {
        const today = data || this.calendar.todayDetails;
        const { hourly } = today.lunar; // This is an array of 'time' objects from Lunar.js

        let activityDataSourced = false;
        let detailsDataSourced = false;

        const processedHourlyDetails = hourly.map(time => {
            const hourData = {
                timeRange: `${time.getMinHm()} - ${time.getMaxHm()}`,
                stemBranch: time.getGanZhi(),
                stemBranchEn: `${chineseToEnglishMapping.heavenlyStems[time.getGan()]} ${chineseToEnglishMapping.earthlyBranches[time.getZhi()]}`,
                activity: "Varies", // Default value
                details: [] // Default empty array
            };

            // Attempt to get lucky status
            if (typeof time.getLucky === 'function') {
                const luckyStatus = time.getLucky(); // This is a guess, actual method name might differ
                // Assuming getLucky() returns something like "吉", "凶", "中" or an object
                if (typeof luckyStatus === 'string') {
                    // Simple mapping, this would need to be more robust based on actual return values
                    if (luckyStatus === "吉") hourData.activity = "Auspicious";
                    else if (luckyStatus === "凶") hourData.activity = "Inauspicious";
                    else hourData.activity = "Neutral";
                    activityDataSourced = true;
                } else {
                    // If it's an object, you might need to inspect its properties
                    // For now, we'll stick to the default if it's not a simple string
                }
            } else if (typeof time.getTianShen === 'function') { // Another guess based on some Lunar.js versions (黄道黑道)
                // Example: getTianShen() might return an object like { name: "青龙", type: "黄道" }
                // Or it might return the name directly: "青龙" (Green Dragon)
                const tianShen = time.getTianShen();
                let shenName = "";
                let shenType = "";

                if (typeof tianShen === 'object' && tianShen !== null && tianShen.name && tianShen.type) {
                    shenName = tianShen.name;
                    shenType = tianShen.type;
                } else if (typeof tianShen === 'string') { // If it directly returns the star name
                    shenName = tianShen;
                    // We might need a mapping for type if only name is provided
                }

                if (shenName) {
                     // Map shenType to activity (e.g., 黄道 often means auspicious)
                    if (shenType === "黄道" || shenType.toLowerCase().includes('good') || shenType.toLowerCase().includes('lucky')) {
                        hourData.activity = `Auspicious (${shenName})`;
                    } else if (shenType === "黑道" || shenType.toLowerCase().includes('bad') || shenType.toLowerCase().includes('unlucky')) {
                        hourData.activity = `Inauspicious (${shenName})`;
                    } else {
                        hourData.activity = shenName; // If type is neutral or unknown
                    }
                    activityDataSourced = true;
                    // Also, add this to details
                    hourData.details.push(shenName);
                    // We won't set detailsDataSourced to true yet, as this is just one aspect
                }
            }


            // Attempt to get hourly stars/influences for the 'details' array
            // This is highly speculative as method names are unknown.
            // Common terms: "值神" (Duty God), "时神" (Hour God), specific star names
            const potentialStarMethods = ['getZhiShen', 'getShiShen', 'getHourStars', 'getShenSha'];
            let foundStars = [];
            for (const methodName of potentialStarMethods) {
                if (typeof time[methodName] === 'function') {
                    const stars = time[methodName](); // Assuming it returns an array of strings or objects
                    if (Array.isArray(stars)) {
                        stars.forEach(star => {
                            if (typeof star === 'string') foundStars.push(star);
                            else if (typeof star === 'object' && star.name) foundStars.push(star.name);
                        });
                    } else if (typeof stars === 'string') { // If it returns a single star name
                        foundStars.push(stars);
                    }
                    if (foundStars.length > 0) detailsDataSourced = true; // Mark if we got anything
                }
            }

            if (foundStars.length > 0) {
                 // If we got TianShen name and it's not already in foundStars, add it.
                if (hourData.details.length > 0 && !foundStars.includes(hourData.details[0])) {
                    hourData.details = hourData.details.concat(foundStars);
                } else if (hourData.details.length === 0) {
                    hourData.details = foundStars;
                }
            }


            // If still no details from dynamic sources, use a placeholder or keep it empty
            if (hourData.details.length === 0) {
                // Example: Keep it empty or add a placeholder
                // hourData.details.push("General influences apply");
            }

            // Fallback for activity if not sourced
            if (!activityDataSourced && hourData.activity === "Varies" && hourData.details.length > 0) {
                // If we have some details but no clear activity, just list the first detail as activity
                // This is a basic heuristic
                // hourData.activity = hourData.details[0];
            }


            return hourData;
        });

        if (!activityDataSourced) {
            console.log("updateHourlyDetails: Could not dynamically source 'activity' for hourly details. It remains static or default. Investigate Lunar.js 'time' object for methods like getLucky() or getTianShen().");
        }
        if (!detailsDataSourced) {
            console.log("updateHourlyDetails: Could not dynamically source nested 'details' (hourly stars/influences). These remain static or default. Investigate Lunar.js 'time' object for methods returning hourly astrological influences.");
        }

        // If after all attempts, details array is empty for an hour, and we had a static list before,
        // we might want to put back some generic static details for those.
        // For now, we'll leave them empty if no dynamic data is found.
        // This part replaces the old static hourlyDetails array with the new processed one.

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
        const lunarInstance = dayData.lunar; // This is 'd' in calendar.js
        if (typeof lunarInstance.getFetalGodDay === 'function') { // Example:胎神日 (Daily Fetal God)
            const fetalGodDay = lunarInstance.getFetalGodDay(); // e.g. 占门碓外东南 (Occupies outside the door stone, Southeast)
             if (fetalGodDay) {
                 directionalStarsItems.push({ star: "Daily Fetal God", direction: fetalGodDay.replace(/.*(占|仓库|房内|厨灶|房床|碓磨|门堂|鸡栖|厕戶|仓库|碓磨|大门|房主).*/, '').trim() || "Varies", comment: fetalGodDay });
             }
        }
         if (typeof lunarInstance.getPengZu === 'function') { // Example: 彭祖百忌 (Peng Zu's Hundred Taboos)
            const pengZu = lunarInstance.getPengZu(); // e.g. 甲不开仓 财物耗亡 (Jia day, don't open warehouse, wealth will be lost)
            if (pengZu) {
                 directionalStarsItems.push({ star: "Peng Zu's Taboos", direction: "Activities to Avoid", comment: pengZu });
            }
        }


        dynamicDailyStarsData.details.push({
            category: "Daily Stars, Auspicious and Inauspicious Directions",
            items: directionalStarsItems
        });

        // 2. Daily Auxiliary Star
        let auspiciousStars = [];
        let inauspiciousStars = [];
        let auxStarsSourced = false;

        if (lunarInstance) {
            // Speculative: try getDayShenSha() or separate good/bad star functions
            if (typeof lunarInstance.getDayShenSha === 'function') { // More common in some libs
                const allShenSha = lunarInstance.getDayShenSha(); // Expects array of {name: string, type: 'good'/'bad'} or similar
                if (Array.isArray(allShenSha)) {
                    allShenSha.forEach(star => {
                        if (typeof star === 'object' && star.name && star.type) {
                            if (star.type.toLowerCase() === 'good' || star.type.toLowerCase() === 'auspicious' || star.type === '吉') {
                                auspiciousStars.push(star.name);
                            } else if (star.type.toLowerCase() === 'bad' || star.type.toLowerCase() === 'inauspicious' || star.type === '凶') {
                                inauspiciousStars.push(star.name);
                            }
                        }
                    });
                    if (auspiciousStars.length > 0 || inauspiciousStars.length > 0) auxStarsSourced = true;
                }
            } else {
                 // Try separate functions if getDayShenSha doesn't exist or doesn't work
                if (typeof lunarInstance.getGoodStars === 'function' && typeof lunarInstance.getBadStars === 'function') {
                    const good = lunarInstance.getGoodStars();
                    const bad = lunarInstance.getBadStars();
                    if (Array.isArray(good)) auspiciousStars = good.map(s => (typeof s === 'object' && s.name) ? s.name : s);
                    if (Array.isArray(bad)) inauspiciousStars = bad.map(s => (typeof s === 'object' && s.name) ? s.name : s);
                    if (auspiciousStars.length > 0 || inauspiciousStars.length > 0) auxStarsSourced = true;
                } else if (typeof lunarInstance.getAuspiciousStars === 'function' && typeof lunarInstance.getInauspiciousStars === 'function') {
                    const good = lunarInstance.getAuspiciousStars();
                    const bad = lunarInstance.getInauspiciousStars();
                     if (Array.isArray(good)) auspiciousStars = good.map(s => (typeof s === 'object' && s.name) ? s.name : s);
                    if (Array.isArray(bad)) inauspiciousStars = bad.map(s => (typeof s === 'object' && s.name) ? s.name : s);
                    if (auspiciousStars.length > 0 || inauspiciousStars.length > 0) auxStarsSourced = true;
                }
            }
        }

        if (!auxStarsSourced) {
            console.log("renderDailyStars: 'Daily Auxiliary Star' data is currently STALE/STATIC. Failed to dynamically source from Lunar.js object. Please investigate methods like getDayShenSha(), getGoodStars()/getBadStars() on the lunar object provided by the library.");
            // Fallback to static data if dynamic sourcing fails
            auspiciousStars = ["Static Moon", "Static Four Phase", "Static Removal God", "Static Heavenly Virtue", "Static Success God", "Static Heavenly Horse"];
            inauspiciousStars = ["Static War Female", "Static Flying Disaster", "Static Sha God", "Static Imprisonment", "Static Lesser Consumer"];
        }

        dynamicDailyStarsData.details.push({
            category: "Daily Auxiliary Star",
            items: auspiciousStars,
            inauspiciousItems: inauspiciousStars
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
