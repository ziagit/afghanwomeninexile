<?php

namespace Database\Seeders;

use App\Models\Activity;
use Illuminate\Database\Seeder;

class ImportedActivitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $activities = [
            [
                'name' => 'Meeting with Richard Bennett',
                'type' => 'meeting',
                'title' => 'Meeting with UN Special Rapporteur Richard Bennett',
                'body' => <<<'TEXT'
It was a significant opportunity to meet with Richard Bennett, the United Nations Special Rapporteur on the situation of human rights in Afghanistan, and discuss the pressing challenges facing Afghan women and girls under the current circumstances. During our meeting, I highlighted the ongoing restrictions imposed on women and girls, including limitations on access to education, employment, public participation, and freedom of movement. I emphasized the profound impact these policies have on the lives, dignity, and future prospects of Afghan women and their broader consequences for Afghan society. We also discussed the situation of women human rights defenders, journalists, activists, former public servants, and other individuals who remain at risk because of their work, beliefs, or advocacy. For more details, see full article.
TEXT,
            ],
            [
                'name' => 'Pakistan Repatriation Statement',
                'type' => 'statement',
                'title' => "Statement of the Movement of Afghanistan Women in Exile Regarding the Government of Pakistan's Decision on the Repatriation of Afghan Refugees",
                'body' => <<<'TEXT'
The Movement of Afghanistan Women in Exile expresses its profound concern regarding the Government of Pakistan's recent decision concerning the repatriation of Afghan refugees. We respectfully note that, given the current humanitarian and human rights situation in Afghanistan, the implementation of large-scale returns may have serious consequences for many vulnerable individuals, including women, children, human rights defenders, civil society activists, journalists, and members of religious and ethnic minority communities.
Afghanistan continues to face significant human rights and humanitarian challenges. Women and girls remain subject to extensive restrictions affecting their access to education, employment, freedom of movement, and participation in public life. Many individuals who have sought refuge abroad continue to face well-founded concerns regarding their safety and security should they be returned under the present circumstances.
TEXT,
            ],
            [
                'name' => 'Herat Protest Statement',
                'type' => 'statement',
                'title' => 'Statement of the Movement of Afghanistan Women in Exile Expressing Concern Over the Reported Suppression of Women Protesters in Herat and Calling for the Immediate Release of Those Detained',
                'body' => <<<'TEXT'
The Movement of Afghanistan Women in Exile expresses its deep concern regarding reports of the suppression of peaceful women protesters in Herat and the detention of a number of women participating in a peaceful public demonstration.
According to available reports, the protest was organized by women seeking to peacefully express their concerns regarding the continued restrictions affecting their fundamental rights and freedoms. The reported use of force against peaceful demonstrators and the subsequent detention of participants raise serious concerns under internationally recognized human rights principles, including the rights to freedom of expression, peaceful assembly, and personal liberty.
TEXT,
            ],
            [
                'name' => "Children's Day Statement",
                'type' => 'statement',
                'title' => "Statement of the Movement of Afghanistan Women in Exile On the Occasion of International Children's Day – 1 June",
                'body' => <<<'TEXT'
On the occasion of International Children's Day, the Movement of Afghanistan Women in Exile joins the international community in reaffirming the fundamental rights of every child and recognizing our shared responsibility to ensure that all children grow up in an environment of safety, dignity, education, and opportunity.
While this day is celebrated around the world as an occasion to promote children's well-being, development, and future, millions of children in Afghanistan—particularly girls—continue to face significant challenges that affect their access to education, protection, and equal opportunities.
TEXT,
            ],
            [
                'name' => 'Hazara Culture Day',
                'type' => 'event',
                'title' => 'Movement of Afghanistan Women in Exile Commemorates Hazara Culture Day',
                'body' => <<<'TEXT'
The Movement of Afghanistan Women in Exile, under the leadership of Ms. Farzana Rezaei, commemorated Hazara Culture Day (19 May) through a special event dedicated to celebrating the rich cultural heritage, history, and identity of the Hazara people.
According to the organizers, Hazara Culture Day is observed annually by Hazara communities and cultural organizations in various parts of the world. Over the years, it has become an important occasion to recognize the contributions of the Hazara community to Afghanistan's cultural diversity while promoting the preservation of its language, traditions, arts, literature, and historical legacy.
TEXT,
            ],
            [
                'name' => 'Joint Spouses Statement',
                'type' => 'joint statement',
                'title' => 'Joint Statement about Regulation on the Separation of Spouses',
                'body' => <<<'TEXT'
We, the undersigned human rights organizations, women's rights organizations, civil society groups, and advocacy movements, express our profound concern and strong objection to the recently issued Regulation on the Separation of Spouses, published in Official Gazette No. 1489 by the de facto authorities in Afghanistan.
This regulation raises serious concerns regarding the protection of the rights of women and children. Rather than safeguarding the institution of the family, several of its provisions appear to undermine fundamental human rights and create conditions that may perpetuate discrimination, coercion, and structural violence against women and children.
TEXT,
            ],
            [
                'name' => 'Gender Apartheid Statement',
                'type' => 'statement',
                'title' => 'Statement of the Movement of Afghanistan Women in Exile Condemning Gender Apartheid, Ethnic and Religious Discrimination, and Exclusionary Policies in Afghanistan',
                'body' => <<<'TEXT'
The Movement of Afghanistan Women in Exile express profound concern over the continued institutionalization of gender discrimination, ethnic and religious exclusion, and policies of monopolization that have undermined human rights, equality, and social cohesion in Afghanistan.
Today, we raise our voices with one clear and united message: Afghanistan is the shared homeland of all its people. It does not belong exclusively to any single ethnic group, religion, or gender. The history, identity, and future of Afghanistan have been shaped by the sacrifices, aspirations, and contributions of all its citizens—women and men, and people of every ethnic and religious community. No individual or group has the right to claim exclusive ownership of the nation or deny others their equal place within it.
TEXT,
            ],
            [
                'name' => 'Academic Year Statement',
                'type' => 'statement',
                'title' => 'Statement of the Movement of Afghanistan Women in Exile On the Occasion of the Beginning of the New Academic Year and the Continued Denial of Education for Afghan Girls',
                'body' => <<<'TEXT'
As the new academic year begins, classrooms around the world reopen, welcoming students with the promise of learning, opportunity, and hope. In Afghanistan, however, millions of girls continue to be denied this fundamental opportunity. For yet another year, secondary schools and universities remain closed to many Afghan girls and young women, depriving them of their internationally recognized right to education.
The Movement of Afghanistan Women in Exile expresses its deep concern over the continued restrictions on girls' access to education. The prolonged exclusion of girls and women from educational institutions has profound implications not only for their individual development and well-being but also for the future social, economic, and cultural development of Afghanistan.
TEXT,
            ],
            [
                'name' => 'Nowruz Statement',
                'type' => 'statement',
                'title' => 'Statement of the Movement of Afghanistan Women in Exile On the Occasion of Nowruz',
                'body' => <<<'TEXT'
Nowruz, one of the oldest and most cherished cultural traditions of the region, is a celebration of renewal, hope, peace, and human solidarity. Observed for centuries by diverse communities, it represents the arrival of spring and symbolizes resilience, coexistence, and the enduring belief in a brighter future. Beyond its cultural significance, Nowruz reflects universal values of mutual respect, compassion, and the peaceful coexistence of all people.
This year's celebration comes at a time when the people of Afghanistan continue to face profound humanitarian and human rights challenges. Afghan women and girls, in particular, continue to experience significant restrictions on the enjoyment of their fundamental rights and freedoms, while many families remain displaced or live in exile, separated from their homes and communities.
TEXT,
            ],
            [
                'name' => 'International Women\'s Day Statement',
                'type' => 'statement',
                'title' => 'Statement of the Movement of Afghanistan Women in Exile On the Occasion of International Women\'s Day – 8 March',
                'body' => <<<'TEXT'
On the occasion of International Women's Day, the Movement of Afghanistan Women in Exile and the NEW Women's Empowerment Office reaffirm their commitment to the universal principles of human rights, equality, dignity, and justice. International Women's Day serves as a global reminder of the enduring efforts of women to achieve equal rights, meaningful participation, and freedom from discrimination in every sphere of life.
This year's commemoration comes at a time when Afghan women and girls continue to face unprecedented challenges. Since the political developments of recent years, they have experienced extensive restrictions affecting their access to education, employment, freedom of movement, participation in public life, and the full enjoyment of their fundamental human rights. These developments have significantly impacted the lives, opportunities, and well-being of millions of women and girls across Afghanistan.
TEXT,
            ],
            [
                'name' => 'Penal Code Statement',
                'type' => 'statement',
                'title' => "Statement by the Movement of Afghanistan Women in Exile on the Taliban's Penal Code",
                'body' => <<<'TEXT'
The Movement of Afghanistan Women in Exile has issued a statement expressing its profound concern over the Penal Code of the Islamic Emirate, describing it as a legal framework that raises serious concerns regarding the protection of fundamental human rights, equality before the law, and the administration of justice in Afghanistan.
According to the statement, several provisions of the Penal Code appear to be inconsistent with internationally recognized principles of due process and fair trial. These include restrictions on the right to legal representation and access to legal counsel, the authorization of corporal and multiple punishments, and provisions that may result in discriminatory treatment, particularly with respect to women and girls. The Movement notes that these provisions also represent a significant departure from Afghanistan's previous legal framework and from internationally accepted standards of justice and the rule of law.
TEXT,
            ],
            [
                'name' => 'Gender Apartheid ICC',
                'type' => 'joint statement',
                'title' => 'Joint Statement Condemning Gender Apartheid, Slavery, and Crimes Against Humanity in Afghanistan',
                'body' => <<<'TEXT'
Addressed to the International Criminal Court (ICC), the United Nations Human Rights Council, the Office of the United Nations High Commissioner for Human Rights (OHCHR), United Nations Member States, the European Union, and the International Community.
We, the undersigned organizations, movements, and civil society representatives, express our profound concern regarding the catastrophic human rights situation in Afghanistan, particularly the systematic and targeted persecution of women, girls, and vulnerable communities. We strongly condemn the policies, laws, and practices imposed by the Taliban that have resulted in the near-total exclusion of women from social, educational, professional, and public life. These deliberate and institutionalized measures have devastated the lives of millions of Afghan women and girls, exposing them to structural violence, extreme poverty, de facto house confinement, forced marriage, and the denial of their most fundamental human rights and dignity.
TEXT,
            ],
            [
                'name' => 'Yalda Statement',
                'type' => 'statement',
                'title' => 'Statement of the Movement of Afghanistan Women in Exile On the Occasion of Yalda Night',
                'body' => <<<'TEXT'
Yalda Night, one of the oldest cultural traditions of the people of Afghanistan and the wider region, symbolizes the triumph of light over darkness and hope over despair. For centuries, this cherished occasion has reflected the values of cultural resilience, social solidarity, and human dignity. Yet, for Afghan women today, Yalda has become more than a celebration—it is a reminder of the profound deprivation, repression, and systematic erasure of their cultural identity and fundamental rights.
In Afghanistan, under the current de facto authorities, women and girls continue to...
TEXT,
            ],
            [
                'name' => 'Human Rights Day Statement',
                'type' => 'statement',
                'title' => 'Statement of the Movement of Afghanistan Women in Exile On the Occasion of Human Rights Day',
                'body' => <<<'TEXT'
On the occasion of Human Rights Day, the Movement of Afghanistan Women in Exile raises its voice on behalf of millions of Afghan women and girls who, both inside Afghanistan and beyond its borders, continue to endure discrimination, violence, and the systematic denial of their fundamental rights. Human Rights Day is a reminder of the universal commitment to protect the dignity and equality of every individual. Yet the current reality in Afghanistan and the region reflects one of the most severe and persistent human rights crises of our time.
TEXT,
            ],
            [
                'name' => 'Violence Against Women Day',
                'type' => 'statement',
                'title' => 'Statement of the Movement of Afghanistan Women in Exile On the Occasion of the International Day for the Elimination of Violence Against Women – 25 November',
                'body' => <<<'TEXT'
On the occasion of the International Day for the Elimination of Violence Against Women, the Movement of Afghanistan Women in Exile reaffirms its unwavering solidarity with all Afghan women and girls who continue to endure severe and systematic violations of their fundamental rights.
Today, violence against Afghan women has become one of the world's most urgent yet insufficiently addressed human rights crises. Under the rule of the Taliban, women and girls continue to face widespread discrimination and restrictions, including the denial of education, exclusion from employment, severe limitations on freedom of movement, and persistent intimidation. These measures have profoundly affected the dignity, well-being, and fundamental freedoms of millions of Afghan women.
TEXT,
            ],
            [
                'name' => 'Chadori Hospital Protest',
                'type' => 'protest',
                'title' => "Protest Statement of the Movement of Afghanistan Women in Exile On the Taliban's Restriction Preventing Women Without a Chadori from Entering Hospitals in Herat",
                'body' => <<<'TEXT'
The Movement of Afghanistan Women in Exile expresses its deep concern and strong condemnation of the reported decision by the Taliban authorities in Herat to prohibit women from entering hospitals unless they are wearing a chadori.
According to credible reports, women are being denied access to healthcare facilities if they are not dressed in a chadori. This measure constitutes a serious violation of women's fundamental rights, including their right to access healthcare, freedom of movement, personal dignity, and equal treatment. Restricting access to essential medical services based on compulsory dress requirements places the health and well-being of countless women at risk.
TEXT,
            ],
            [
                'name' => 'Peoples Tribunal Support',
                'type' => 'joint statement',
                'title' => 'Joint Statement of 115 Civil Society Organizations, Associations, Human Rights Groups, Protest Movements, and Diaspora Organizations in Support of the People\'s Tribunal in Spain',
                'body' => 'No excerpt shown on the listing page - full statement only available on the article page.',
            ],
            [
                'name' => 'Maryam Mohammadi Memorial',
                'type' => 'memorial',
                'title' => "In Memory of Maryam Mohammadi: Honoring a Survivor of the Sayed Al-Shuhada School Attack",
                'body' => <<<'TEXT'
It is with profound sadness that we have learned of the passing of Maryam Mohammadi, one of the survivors of the tragic attack on the Sayed Al-Shuhada Girls' School in western Kabul. After enduring more than four years of severe physical injuries and prolonged suffering, Maryam passed away on 1 October 2025.
Maryam was critically injured during the attack on 8 May 2021, when a series of explosions targeted the Sayed Al-Shuhada Girls' School in the predominantly Hazara neighborhood of Dasht-e-Barchi, Kabul. The attack resulted in the deaths of 85 schoolgirls and left more than 147 others injured. The victims were primarily girls between the ages of 6 and 18 who had been attending school in pursuit of their education.
TEXT,
            ],
            [
                'name' => 'Earthquake Condolence',
                'type' => 'condolence',
                'title' => 'Statement of Condolence and Solidarity by the Movement of Afghanistan Women in Exile',
                'body' => <<<'TEXT'
The Movement of Afghanistan Women in Exile expresses its profound sorrow and heartfelt condolences following the devastating earthquake that struck the provinces of Balkh and Samangan, resulting in the tragic loss of lives, injuries to many others, and widespread destruction of homes and livelihoods.
TEXT,
            ],
            [
                'name' => 'Teachers Day Statement',
                'type' => 'statement',
                'title' => "Statement of the Women's Movement of Afghanistan in Exile on the Occasion of Teacher's Day",
                'body' => <<<'TEXT'
As we commemorate Teacher's Day, we do so at a time when in our homeland, Afghanistan, the light of knowledge has been extinguished in many homes, and the voices of teachers and students have fallen into enforced silence.
Today, thousands of Afghan girls are deprived of their natural right to education, and teachers — these beacons of awareness — are either living in hardship and isolation or fading into the bitter silence of neglect.
TEXT,
            ],
            [
                'name' => 'Sima Noori Appreciation',
                'type' => 'appreciation',
                'title' => 'Statement of Appreciation by the Movement of Afghanistan Women in Exile on the Visit of Sima Noori',
                'body' => <<<'TEXT'
The Movement of Afghanistan Women in Exile extends its sincere appreciation and gratitude for the visit of the delegation representing the Gathering of Taliban Opposition Groups in Islamabad, with special recognition of Sima Noori, a member of the leadership of the gathering, for her meaningful and compassionate engagement with Afghan women in exile.
During her visit, Sima Noori met with protesting Afghan women and girls who are currently living under difficult and uncertain circumstances in Pakistan, where many continue to face insecurity and the risk of forced deportation. Accompanied by the leaders of several protest movements—including Farzana Rezaei, Rahel Talash, Safia Arefi, and Latifa Shojaei—she visited women in Punjab, Sadiqabad, and Faisal Town to hear directly about their experiences, offer encouragement, and express solidarity with their peaceful struggle.
TEXT,
            ],
            [
                'name' => 'Hazara Persecution Statement',
                'type' => 'statement',
                'title' => 'Statement of the Movement of Afghanistan Women in Exile On the Ongoing Persecution of the Hazara Community',
                'body' => <<<'TEXT'
The Movement of Afghanistan Women in Exile strongly condemns the systematic persecution and violence targeting the Hazara community, which has persisted over past decades and, according to numerous reports by human rights organizations and observers, continues under the current de facto authorities in Afghanistan. We express our deep concern over the serious risks, discrimination, forced displacement, and humanitarian challenges faced by Hazaras both within Afghanistan and in neighboring countries.
TEXT,
            ],
            [
                'name' => 'Democracy Day Statement',
                'type' => 'statement',
                'title' => 'Statement of the Movement of Afghanistan Women in Exile On the Occasion of the International Day of Democracy – 15 September 2025',
                'body' => <<<'TEXT'
The International Day of Democracy serves as a reminder of the fundamental values of freedom, equality, human dignity, and the meaningful participation of all people in shaping their political and social future. Yet, in Afghanistan today, these principles have been systematically dismantled. Women and girls, who constitute half of the country's population, have been deprived not only of their rights to education, employment, and public participation, but have also been effectively stripped of their status as equal citizens. Afghanistan has entered one of the darkest chapters of its history, where the principles of democracy are fundamentally denied.
TEXT,
            ],
            [
                'name' => 'Internet Shutdown Protest',
                'type' => 'protest',
                'title' => "Protest Statement of the Women's Movement of Afghanistan in Exile Regarding the Internet Shutdown by the Taliban in Mazar-e-Sharif!",
                'body' => <<<'TEXT'
The Women's Movement of Afghanistan in Exile strongly condemns, in the strongest terms, the Taliban's action in cutting off the internet in the city of Mazar-e-Sharif. Through this inhumane act, the Taliban seeks to eliminate online education, block the people's means of communication, and silence the voices of advocacy of women and citizens.
TEXT,
            ],
            [
                'name' => 'Forced Deportation Statement',
                'type' => 'statement',
                'title' => 'Official Statement of the Women\'s Movement of Afghanistan in Exile Regarding the Forced Deportation of Migrants from Pakistan',
                'body' => <<<'TEXT'
We, the members and representatives of the Women's Movement of Afghanistan in Exile, express our utmost concern and sorrow regarding the decision and process of the forced deportation of Afghan migrants from Pakistan, and hereby declare our position.
We, the migrants of Afghanistan—including civil activists, journalists, human rights defenders, and employees of governmental and international institutions—were compelled to leave our country following the fall of the Republic system in 2021 and the direct threats posed by the Taliban group. Over the past four years, we have lived in a state of uncertainty, without security and without a clear future. Neighboring countries, particularly Pakistan, have hosted us during this period, and we have lived in full compliance with the domestic laws of those countries.
TEXT,
            ],
            [
                'name' => 'Identity Card Photos Statement',
                'type' => 'statement',
                'title' => "Statement of the Movement of Afghanistan Women in Exile on the Taliban's Decision to Remove Women's Photographs from National Identity Cards",
                'body' => <<<'TEXT'
The Movement of Afghanistan Women in Exile has issued a statement strongly condemning the Taliban's recent decision to remove women's photographs from Afghanistan's national identity cards, describing it as a clear violation of fundamental human rights and part of what the Movement characterizes as the Taliban's broader policy of gender apartheid.
The statement notes that, under Article 46 of the Constitution of Afghanistan, the national identity card (Tazkira) serves as the official proof of identity for every citizen and is not determined by gender. It further refers to Article 22 of the Constitution, which prohibits discrimination between women and men. According to the Movement, removing women's photographs from identity documents amounts to the denial of the identity of half of Afghanistan's population.
TEXT,
            ],
            [
                'name' => 'Diplomatic Privileges Concern',
                'type' => 'concern',
                'title' => 'Concerns Raised Over Reported Diplomatic Privileges for Taliban Affiliated Women Amid Continued Restrictions on Afghan Women',
                'body' => <<<'TEXT'
Recent reports by Sarah Adams, a former officer of the U.S. Central Intelligence Agency (CIA), have generated significant public attention following the publication of a series of images alleging that a number of women affiliated with the Taliban have been issued diplomatic passports. According to the reports, these documents have been granted to individuals closely associated with the Taliban's leadership, prompting renewed public discussion regarding unequal treatment, transparency, and the group's internal policies.
These reports emerge at a time when the Taliban have reportedly implemented regulations prohibiting the inclusion of women's photographs on Afghanistan's national identity cards. The apparent contrast between restricting the identity rights of ordinary Afghan women while extending diplomatic privileges to women connected to the ruling authorities has raised concerns among observers and human rights advocates about inconsistencies in the implementation of these policies.
Many commentators view this apparent disparity as reflective of a broader pattern in which the rights and freedoms of Afghan women continue to be significantly restricted, while exceptions may be afforded to individuals with close ties to those in positions of authority. They argue that such practices undermine principles of equality, transparency, and non-discrimination.
The issue has further intensified calls from civil society organizations and human rights advocates for greater international attention to the condition of Afghan women and for consistent adherence to fundamental human rights standards, including equality before the law and equal recognition of personal identity without discrimination.
TEXT,
            ],
            [
                'name' => 'Green Party Support',
                'type' => 'statement',
                'title' => 'Statement of Movement of Afghanistan Women in Exile in Solidarity and Support for the Green Party of Afghanistan in Exile',
                'body' => <<<'TEXT'
Today, we have come together to express our support for the principles and policies of the Green Party of Afghanistan in Exile and to reaffirm, with a unified voice, our shared commitment to democracy, human rights, social justice, environmental sustainability, and a peaceful future for Afghanistan.
Afghanistan is currently facing one of the most challenging periods in its contemporary history.
Women and girls continue to be deprived of many of their fundamental rights and freedoms.
Ethnic and religious minorities, as well as individuals expressing diverse views, continue to face discrimination and restrictions.
The country is experiencing a severe economic crisis, widespread poverty, and an ongoing humanitarian emergency.
At the same time, Afghanistan's environment, natural resources, water reserves, forests, and agricultural lands are increasingly threatened by environmental degradation and climate-related challenges.
TEXT,
            ],
            [
                'name' => 'Fourth Anniversary Statement',
                'type' => 'statement',
                'title' => "Statement of the Movement of Afghanistan Women in Exile on the Occasion of the Fourth Anniversary of the Collapse of the Islamic Republic of Afghanistan and the Taliban's Return to Power",
                'body' => <<<'TEXT'
Afghanistan has plunged into the darkness of ignorance and regression—a darkness marked by the closure of schools and universities to girls, the exclusion of women from work and public life, intensified repression of ethnic and religious minorities, and widespread violations of human rights in Afghanistan.
We stand here to declare that the women and people of Afghanistan will never surrender to discrimination and oppression, and that the international community must not ignore this catastrophe.
We strongly condemn the Taliban's regressive regime for its misogynistic policies, systematic human rights violations, and the exclusion of women from the country's social and political life.
TEXT,
            ],
            [
                'name' => 'August 15 Event',
                'type' => 'event',
                'title' => 'Special Event Marking the Fourth Anniversary of the August 15 Developments in Afghanistan, Organized by the Movement of Afghanistan Women in Exile in Pakistan',
                'body' => <<<'TEXT'
On 15 August, marking the fourth anniversary of the developments in Afghanistan, the Movement of Afghanistan Women in Exile organized a special event in Islamabad, Pakistan. The program was dedicated to reflecting on the challenges, resilience, and aspirations of Afghan women in the face of continuing restrictions and human rights concerns.
The event commenced with the screening of a documentary highlighting the lived experiences of Afghan women, many of whom have endured severe restrictions, violence, and exclusion since the developments of August 2021. Through powerful imagery and personal testimonies, the documentary sought to amplify voices that have too often been silenced and to raise awareness of the realities faced by Afghan women and girls.
TEXT,
            ],
            [
                'name' => 'Do Not Recognise Taliban',
                'type' => 'statement',
                'title' => 'Do not Recognise Taliban',
                'body' => <<<'TEXT'
As an Afghan woman, I cannot recognize the Taliban as a legitimate government. Their rule has systematically denied women and girls their most fundamental rights: the right to education, to work, to participate freely in public life, and to shape the future of their own country. To recognize them would not only be a political act but also a betrayal of justice, dignity, and equality. It would mean accepting a system that has silenced half the population and condemned millions of Afghan women to invisibility.
TEXT,
            ],
            [
                'name' => 'ICC Taliban Ruling',
                'type' => 'statement',
                'title' => 'Statement of the Movement of Afghanistan Women in Exile On the Recent Ruling of the International Criminal Court Against Taliban Leaders',
                'body' => <<<'TEXT'
The Movement Afghanistan Women in Exile welcomes the recent decision by the International Criminal Court (ICC) against certain leaders of the Taliban. We view this ruling as a hopeful step toward justice, accountability, and the formal recognition of the crimes committed against the people of Afghanistan — especially its women.
At a time when the Russian Federation ignores the Taliban's structural atrocities by officially recognizing this terrorist group, and while the United Nations holds ambiguous, victim-neglecting political dialogues on Afghanistan's future, the issuance of this ruling by an international judicial body kindles a spark of hope in the hearts of millions of Afghan women and girls — those whose very existence, rights, and human dignity have been denied and trampled by the Taliban.
TEXT,
            ],
            [
                'name' => 'Russia Recognition Response',
                'type' => 'statement',
                'title' => 'Statement by the Movement of Afghanistan Women in Exile: In Response to the Recognition of the Taliban by the Russian Federation',
                'body' => <<<'TEXT'
A serious question arises: On what legal, ethical, or humanitarian grounds does Russia recognize a fascist, misogynistic, and terrorist group whose hands are stained with the blood of thousands of innocent people and who bear full responsibility for the destruction of an entire nation?
TEXT,
            ],
            [
                'name' => 'Iran Deportation Response',
                'type' => 'statement',
                'title' => 'Statement by the Movement of Afghanistan Women in Exile in Response to the Forced Deportation of Afghan Refugees by the Islamic Republic of Iran',
                'body' => <<<'TEXT'
At a time when our country is under the complete control of the Taliban—where there is no space for safety, freedom, or human dignity—and Afghan women and girls are deprived of their most basic human rights, Iran's mass deportation of refugees is not only immoral and inhumane but also a blatant violation of international principles and obligations regarding the protection of asylum seekers and displaced persons.
Thousands of Afghan women, men, and children who fled due to threats of violence, torture, gender-based persecution, and the stripping of their freedoms are now being forcibly returned to Afghanistan by the Iranian government—without any safe prospect of return. For them, especially women and girls, what awaits in Afghanistan is oppression, humiliation, repression, and often, death.
TEXT,
            ],
            [
                'name' => 'Women in Diplomacy Statement',
                'type' => 'statement',
                'title' => 'Statement by the Afghan Women in Exile Movement On the Occasion of June 24 – International Day of Women in Diplomacy',
                'body' => <<<'TEXT'
While the world commemorates June 24 as the International Day of Women in Diplomacy, and speaks of women's participation in global decision-making, we – the women of Afghanistan – raise our voices in pain for a truth that remains hidden or ignored by the world.
Today in Afghanistan, not only is diplomacy absent, but women have been erased from public life.
They have been pushed out of negotiating tables, the education system, workplaces even out of the streets and marketplaces.
TEXT,
            ],
            [
                'name' => 'Victims of Torture Statement',
                'type' => 'statement',
                'title' => 'On the Occasion of June 26 – International Day in Support of Victims of Torture',
                'body' => <<<'TEXT'
The resolution states that Afghan women — especially following the Taliban's takeover — have been subjected to the worst forms of psychological, sexual, and physical violence simply for seeking education, raising their voices in protest, or demanding freedom.
According to the movement, many women have been arrested, flogged, raped, humiliated, and even killed for these reasons.
TEXT,
            ],
            [
                'name' => 'World Refugee Day Statement',
                'type' => 'statement',
                'title' => 'Statement by the Afghanistan Women Movement in Exile On the Occasion of June 20 – World Refugee Day',
                'body' => <<<'TEXT'
At a time when millions remain displaced, without protection or recognition — vulnerable to forced returns, arbitrary detention, discrimination, and systemic violence — we believe the continued silence of the international community is deeply troubling and cannot be justified. This letter, supported by more than 80 organisations, movements, and grassroots groups, is addressed to international agencies, UNHCR member states, and independent media, urging immediate attention and action.
TEXT,
            ],
            [
                'name' => 'Eid al-Adha Message',
                'type' => 'message',
                'title' => 'Eid al-Adha Message of Congratulations',
                'body' => <<<'TEXT'
Eid al-Adha comes at a time when the women of Afghanistan continue to live under the shadow of deprivation, discrimination, and enforced silence. Yet despite all these pressures, their voices of resistance, awareness, and struggle have not been silenced and will not be silenced.
TEXT,
            ],
            [
                'name' => 'Shahr-e-Naw Arrests',
                'type' => 'statement',
                'title' => 'Statement of the Movement of Afghanistan Women in Exile: In Response to the Arrest of Girls and Women in Shahr-e-Naw, Kabul',
                'body' => <<<'TEXT'
We emphasize that depriving women of their rights to education, work, freedom of movement, and public participation is a flagrant violation of fundamental human rights and contradicts Afghanistan's obligations as a member of the United Nations under international treaties and conventions.
According to Article 24 of the 2004 Constitution of the Islamic Republic of Afghanistan: [statement continues on article page]
TEXT,
            ],
            [
                'name' => 'Open Letter of Civil Society',
                'type' => 'open letter',
                'title' => 'Official Open Letter of Civil Society Organizations',
                'body' => <<<'TEXT'
Ninety-six civil society organizations, protest movements, and human rights groups from both inside and outside Afghanistan have jointly published an official open letter in three languages: Persian, English, and German. The letter clearly expresses their opposition to any recognition of the Taliban and, at the same time, warns against the continuing forced deportation of Afghan refugees and asylum seekers.
TEXT,
            ],
            [
                'name' => 'University Entrance Results Statement',
                'type' => 'statement',
                'title' => 'Statement of Movement of Afghanistan Women in Exile on the Announcement of the National University Entrance Examination Results Without the Participation of Women',
                'body' => <<<'TEXT'
The continued denial of higher education to women and girls, who constitute nearly half of Afghanistan's population, cannot be justified under any legitimate legal, moral, or religious framework. The exclusion of women from education under the pretext of religion does not reflect the principles of faith; rather, it represents a misuse of religious teachings to institutionalize discrimination and reinforce unequal treatment.
TEXT,
            ],
            [
                'name' => 'Taliban Supporter Quote',
                'type' => 'statement',
                'title' => 'Taliban Supporter\'s Justification for the Arrest of Women: "A Woman Has No Right to Look at the Road with Both Eyes"',
                'body' => <<<'TEXT'
Recent remarks by a Taliban supporter claiming that "a woman has no right to look at the road with both eyes" reflect the continued escalation of discriminatory attitudes and restrictions imposed on women and girls in Afghanistan.
These statements illustrate a broader pattern of policies that seek to gradually erase women from public life. What began with restrictions on women's education, employment, freedom of movement, and participation in society has expanded to increasingly intrusive limitations on their appearance, voices, and even their ability to exist freely in public spaces.
TEXT,
            ],
            [
                'name' => 'Forest Inspiration',
                'type' => 'reflection',
                'title' => 'Inspiration from the Forest for the Women of Afghanistan',
                'body' => <<<'TEXT'
Within every forest lies a hidden story—a story that can only be heard by those who listen with calm hearts and seen by those who look with hopeful eyes.
Many people believe that the "law of the jungle" means the rule of the strong over the weak.
TEXT,
            ],
            [
                'name' => 'Flag Day Statement',
                'type' => 'statement',
                'title' => "Statement of the Movement of Afghanistan Women in Exile On the Occasion of Afghanistan's Three color Flag Day",
                'body' => <<<'TEXT'
The black, red, and green flag has, for generations, symbolized the hopes of Afghans for independence, dignity, justice, and national unity. For many citizens, it remains a powerful reminder of the values of freedom, equality, and the pursuit of a peaceful and inclusive future.
TEXT,
            ],
            [
                'name' => 'World Refugee Day 2025',
                'type' => 'joint statement',
                'title' => 'Joint Statement on the Occasion of World Refugee Day 20 June 2025',
                'body' => <<<'TEXT'
In recent months, credible reports from international organizations and reliable sources have documented a significant increase in the forced deportation, arbitrary detention, mistreatment, and systematic violations of the fundamental rights of Afghan migrants. Many of these measures have reportedly been carried out without due process, individual assessment, or adequate consideration of the specific vulnerabilities and protection needs of those affected.
TEXT,
            ],
            [
                'name' => 'Kobra Rezai Death',
                'type' => 'statement',
                'title' => 'Statement of the Movement of Afghanistan Women in Exile on the Death of Kobra Rezai',
                'body' => <<<'TEXT'
According to her family, parts of Ms. Rezai's remains have not yet been recovered, and several of her personal belongings, including her mobile phone and identification documents, remain missing. Reports further indicate that two individuals have been taken into custody in connection with the case, while the investigation is continuing.
TEXT,
            ],
            [
                'name' => 'Inauguration Party',
                'type' => 'event',
                'title' => 'Inauguration party of Movement of Afghanistan women in exile',
                'body' => <<<'TEXT'
The Movement of Afghanistan Women in Exile was officially inaugurated in Islamabad, Pakistan, under the leadership of Ms. Farzana Rezaei. The movement brings together a diverse group of Afghan women committed to promoting human rights, gender equality, and the meaningful participation of women in building a peaceful and inclusive future for Afghanistan.
TEXT,
            ],
        ];

        foreach ($activities as $activity) {
            Activity::query()->updateOrCreate(
                ['title' => $activity['title']],
                $activity + ['image' => null]
            );
        }
    }
}
