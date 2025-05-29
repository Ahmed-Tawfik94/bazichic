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
        const {date , lunar, clashes, positions} = today

        // Example data (replace with actual logic or data from the server)
        const details = {
            fullDate:date.full,
            dongGong:lunar.officer.full,
            pillar: `${lunar.branch} ${lunar.stem}`,
            action:'',
            conflict: `${clashes.clash_two.branch} ${ clashes.clash_two.branch_en }`,
            yearBranch: `${lunar.year.branch} ${chineseToEnglishMapping.earthlyBranches[lunar.year.branch]}`,
            dayBranch: `${lunar.branch} ${chineseToEnglishMapping.earthlyBranches[lunar.branch]}`,
            dayStem: `${lunar.stem} ${chineseToEnglishMapping.heavenlyStems[lunar.stem]}`,
            recommendedActions: [
                "Connect to Nobleman",
                "Express your unhappiness to someone, once."
            ],
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

        // Construct HTML for the details section
        const template = $("#dynamic_date").html();
        const html = Mustache.render(template, { details });
        // Inject the generated HTML into the details section
        $("#dynamic-details").html(html);
    }

    updateHourlyDetails(data) {
        const today = data || this.calendar.todayDetails
        const{hourly} = today.lunar
        const hourlyDetails = [
            {
                timeRange: "12am-12:59am",
                stemBranch: "丙子 (Bing Zi)", // Fire Rat
                activity: "Neutral",
                details: ["Green Dragon", "Five Harmony", "Prosperity"]
            },
            {
                timeRange: "1am-2:59am",
                stemBranch: "丁丑 (Ding Chou)", // Fire Ox
                activity: "Following, Auspicious",
                details: ["Bright Hall", "Heavenly Noble"]
            },
            {
                timeRange: "3am-4:59am",
                stemBranch: "戊寅 (Wu Yin)", // Earth Tiger
                activity: "Abundance, Auspicious",
                details: ["Day Establish", "Heavenly Punishment"]
            },
            {
                timeRange: "5am-6:59am",
                stemBranch: "己卯 (Ji Mao)", // Earth Rabbit
                activity: "Regulate, Neutral",
                details: ["Red Phoenix", "Favorable for Relationships"]
            },
            {
                timeRange: "7am-8:59am",
                stemBranch: "庚辰 (Geng Chen)", // Metal Dragon
                activity: "Daily, Auspicious",
                details: ["Golden Lock", "New Beginnings", "Protection Energy"]
            },
            {
                timeRange: "9am-10:59am",
                stemBranch: "辛巳 (Xin Si)", // Metal Snake
                activity: "Great Reward, Auspicious",
                details: ["Precious Light", "Heavenly Wealth", "Career Growth"]
            },
            {
                timeRange: "11am-12:59pm",
                stemBranch: "壬午 (Ren Wu)", // Water Horse
                activity: "Wind, Auspicious",
                details: ["Heavenly Officer", "Three Combo", "Prosperity"]
            },
            {
                timeRange: "1pm-2:59pm",
                stemBranch: "癸未 (Gui Wei)", // Water Goat
                activity: "Drive, Auspicious",
                details: ["Heavenly Noble", "Road Emptiness", "Group Engagement"]
            },
            {
                timeRange: "3pm-4:59pm",
                stemBranch: "甲申 (Jia Shen)", // Wood Monkey
                activity: "Yet to Be Accomplished, Bad",
                details: ["Day Wealth", "Day Breaker", "Road Challenges"]
            },
            {
                timeRange: "5pm-6:59pm",
                stemBranch: "乙酉 (Yi You)", // Wood Rooster
                activity: "Retreat, Inauspicious",
                details: ["Black Tortoise", "Five Harmony", "Calm Reflection"]
            },
            {
                timeRange: "7pm-8:59pm",
                stemBranch: "丙戌 (Bing Xu)", // Fire Dog
                activity: "Bad",
                details: ["Life Governor", "Five Do Not Meet Hour", "Three Combo"]
            },
            {
                timeRange: "9pm-10:59pm",
                stemBranch: "丁亥 (Ding Hai)", // Fire Pig
                activity: "Delight, Auspicious",
                details: ["Six Combo", "Grappling Hook", "Harmony Time"]
            },
            {
                timeRange: "11pm-11:59pm",
                stemBranch: "戊子 (Wu Zi)", // Earth Rat
                activity: "Beginning, Auspicious",
                details: [
                    "Heavenly Noble",
                    "Initiation Time",
                    "Red Phoenix Blessing",
                    "Favorable for Planning",
                    "Good Luck Enhancer"
                ]
            }
        ];
        hourlyDetails.forEach((hour,idx)=>{
            var time = hourly[idx];
            hour.timeRange = time.getMinHm() + ' - ' + time.getMaxHm();
            hour.stemBranch = time.getGanZhi();
            hour.stemBranchEn=`${chineseToEnglishMapping.heavenlyStems[time.getGan()]} ${chineseToEnglishMapping.earthlyBranches[time.getZhi()]}`

        })
        const template = $("#hourly").html();
        const html = Mustache.render(template, { hourly_details: hourlyDetails });
        $("#hourly-details-grid").html(html);
    }

    renderBaZiList(date) {
        const baziData = [
            {
                title: "Hour",
                stemBranch: "丙子 (Bing Zi)",
                description: "Neutral",
                details: ["Green Dragon", "Five Harmony", "Prosperity"],
            },
            {
                title: "Day",
                stemBranch: "丙申 (Bing Shen)",
                description: "Relief, Auspicious",
                details: ["Bright Hall", "Heavenly Noble"],
                highlight: true, // Mark this item for highlighting
            },
            {
                title: "Month",
                stemBranch: "己卯 (Ji Mao)",
                description: "Regulate, Good/Bad",
                details: ["Red Phoenix"],
            },
            {
                title: "Year",
                stemBranch: "辛巳 (Xin Si)",
                description: "Waiting, Bad",
                details: ["Precious Light"],
            },
            {
                title: "XKDG HETU",
                stemBranch: "甲申 (Jia Shen)",
                description: "Not Yet Accomplished",
                details: ["Wood Monkey"],
            },
            {
                title: "XKDG COMBO 10",
                stemBranch: "戊申 (Wu Shen)",
                description: "Dispensing, Auspicious",
                details: ["Earth Monkey"],
            },
            {
                title: "Combo Pillar",
                stemBranch: "辛巳 (Xin Si)",
                description: "Great Reward, Auspicious",
                details: ["Metal Snake"],
            },
            {
                title: "Clash Pillar",
                stemBranch: "壬寅 (Ren Yin)",
                description: "Fellowship",
                details: ["Water Tiger"],
                highlight: true, // Mark this item for highlighting
            },
            {
                title: "Clash Pillar",
                stemBranch: "庚寅 (Geng Yin)",
                description: "Digital Tiger, Auspicious",
                details: ["Metal Tiger"],
            },
            {
                title: "Select Your BaZi Chart",
                stemBranch: "丙寅 (Bing Yin)",
                description: "Earth Tiger",
                details: ["Abundance"],
            },
            // Add more items here dynamically as needed
        ];

        const container = document.getElementById("bazi-list");
        container.innerHTML = ""; // Clear existing content

        baziData.forEach((item) => {
            // Create a wrapper div
            const itemDiv = document.createElement("div");
            itemDiv.classList.add("bazi-item");
            if (item.highlight) {
                itemDiv.classList.add("highlight"); // Add highlight class if marked
            }

            // Add title
            const title = document.createElement("h3");
            title.textContent = item.title;
            itemDiv.appendChild(title);

            // Add stem and branch description
            const stemBranch = document.createElement("div");
            stemBranch.classList.add("bazi-info");
            stemBranch.innerHTML = `<span>${item.stemBranch}</span> <span>${item.description}</span>`;
            itemDiv.appendChild(stemBranch);

            // Add details list
            const detailsList = document.createElement("ul");
            item.details.forEach((detail) => {
                const detailItem = document.createElement("li");
                detailItem.textContent = detail;
                detailsList.appendChild(detailItem);
            });
            itemDiv.appendChild(detailsList);

            // Append to the container
            container.appendChild(itemDiv);
        });
    }

    renderDailyStars(date) {
        const dailyStarsData = {
            date: "28 March 2025",
            details: [
                {
                    category: "Daily Stars, Auspicious and Inauspicious Directions",
                    items: [
                        { star: "Yellow Path", direction: "North", comment: "Heavenly Jail" },
                        { star: "Heavenly Virtue", direction: "North 3", comment: "God of Happiness" },
                        { star: "Heavenly Virtue Combo", direction: "Centre", comment: "God of Prosperity" },
                        { star: "Monthly Virtue", direction: "Northwest 3", comment: "God of Wealth" },
                        { star: "Monthly Virtue Combo", direction: "South 3", comment: "Yang Noble" },
                        { star: "Great Sun at Sitting", direction: "Northwest 3", comment: "Yin Noble" },
                        { star: "Great Moon at Sitting", direction: "Northwest 3", comment: "Conflict Direction" },
                    ],
                },
                {
                    category: "Daily Auxiliary Star",
                    items: [
                        "Moon",
                        "Four Phase",
                        "Removal God",
                        "Heavenly Virtue",
                        "Success God",
                        "Heavenly Horse",
                        "Important Stability",
                        "Heavenly Relief",
                        "Internal Relief",
                        "Heavenly Rooster",
                        "Branch Virtue",
                        "God Presence Day",
                        "System Day",
                        "Bright Barking Day",
                    ],
                    inauspiciousItems: [
                        "War Female",
                        "Flying Disaster",
                        "Sha God",
                        "Imprisonment",
                        "Lesser Consumer",
                        "Robbery Sha",
                        "White Tiger",
                        "Sky Club",
                        "Tomb Gate",
                        "Withered Bones",
                        "Five Separates",
                    ],
                },
            ],
        };

        const container = document.getElementById("daily-stars");
        container.innerHTML = ""; // Clear existing content

        // Add the date header
        const dateHeader = document.createElement("h3");
        dateHeader.textContent = `Date: ${dailyStarsData.date}`;
        dateHeader.classList.add("date-header");

        // Loop through the details
        dailyStarsData.details.forEach((section) => {
            // Create a section title
            const sectionTitle = document.createElement("p");
            sectionTitle.innerHTML = `<strong>${section.category}</strong>`;
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
