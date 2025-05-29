<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\CoreExtension;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;
use Twig\TemplateWrapper;

/* dashboard.twig */
class __TwigTemplate_3de17e82b622e678285b69701fe2c150 extends Template
{
    private Source $source;
    /**
     * @var array<string, Template>
     */
    private array $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->blocks = [
            'content' => [$this, 'block_content'],
            'jsfooter' => [$this, 'block_jsfooter'],
        ];
    }

    protected function doGetParent(array $context): bool|string|Template|TemplateWrapper
    {
        // line 1
        return "dashboard_layout.twig";
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $this->parent = $this->loadTemplate("dashboard_layout.twig", "dashboard.twig", 1);
        yield from $this->parent->unwrap()->yield($context, array_merge($this->blocks, $blocks));
    }

    // line 2
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_content(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 3
        yield "<!-- Notice -->
";
        // line 4
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "warning", [], "any", false, false, false, 4)) {
            // line 5
            yield "\t<div class=\"row\">
\t\t<div class=\"col-md-12\">
\t\t\t<div class=\"notification success closeable margin-bottom-30\">
\t\t\t\t<h5>
\t\t\t\t\t<strong>
\t\t\t\t\t\t<i class=\"fa fa-bell\"></i>
\t\t\t\t\t\tALERT
\t\t\t\t\t</strong>
\t\t\t\t</h5>
\t\t\t\t<p>
\t\t\t\t\t<strong>";
            // line 15
            yield CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "warning", [], "any", false, false, false, 15);
            yield "
\t\t\t\t\t</strong>
\t\t\t\t</p>
\t\t\t\t<a class=\"close\" href=\"#\"></a>
\t\t\t</div>
\t\t</div>
\t</div>
";
        }
        // line 23
        yield "
<!-- Notice -->
";
        // line 25
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "trialMessage", [], "any", false, false, false, 25)) {
            // line 26
            yield "\t<div class=\"row\">
\t\t<div class=\"col-md-12\">
\t\t\t<div class=\"notification success margin-bottom-30\">
\t\t\t\t<h5>
\t\t\t\t\t<strong>
\t\t\t\t\t\t<i class=\"fa fa-bell\"></i>
\t\t\t\t\t\tATTENTION
\t\t\t\t\t</strong>
\t\t\t\t</h5>
\t\t\t\t<p>
\t\t\t\t\t<strong>";
            // line 36
            yield CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "trialMessage", [], "any", false, false, false, 36);
            yield "
\t\t\t\t\t</strong>
\t\t\t\t</p>
\t\t\t</div>
\t\t</div>
\t</div>
";
        }
        // line 43
        yield "
";
        // line 44
        if ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "thisUser", [], "any", false, false, false, 44), "type", [], "any", false, false, false, 44) == "Åffiliate")) {
            // line 45
            yield "\t<!-- Start Affiliate Content -->
\t<div class=\"row\">
\t\t<div class=\"col-lg-6\">
\t\t\t<div class=\"bazicard\">
\t\t\t\t<div class=\"bazicard\" style=\"margin-bottom:20px;\">
\t\t\t\t\t<div style=\"padding: 20px 20px 20px 20px;text-align: center;\" class=\"center firstTour\">
\t\t\t\t\t\t<h1 style=\"text-align: center;font-weight: 500;font-size:50px;margin-top:0px;\">
\t\t\t\t\t\t\t<i class=\"im im-icon-Conference\"></i>
\t\t\t\t\t\t</h1>
\t\t\t\t\t\t<h3 style=\"text-align:center;\">Start as an Affiliate</h3>
\t\t\t\t\t\t<h4>Generate referrals code that you can share with anyone. Win reward points and credits that you will redeem with Bazichic.</h4>
\t\t\t\t\t\t<div class=\"text-center margin-top-20 margin-bottom-20\" style=\"display:inline-block;\">
\t\t\t\t\t\t\t<a style=\"display:inline-block;\" id=\"startTourBtn\" class=\"button button-3d button-green\" type=\"submit\">
\t\t\t\t\t\t\t\t<i class=\"fa fa-arrow-circle-right\"></i>
\t\t\t\t\t\t\t\tStart Tour</a>

\t\t\t\t\t\t\t<a href=\"";
            // line 61
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
            yield "/my-connections\" class=\"button button-dark\" style=\"display:inline-block;\">
\t\t\t\t\t\t\t\t<i class=\"fa fa-edit\"></i>My Referrals</a>


\t\t\t\t\t\t</div>
\t\t\t\t\t</div>
\t\t\t\t</div>
\t\t\t</div>


\t\t</div>

\t\t<div class=\"col-lg-6\">
\t\t\t<div class=\"bazicard secondTour\" style=\"margin-bottom:20px;\">
\t\t\t\t<div style=\"padding: 20px 20px 20px 20px;text-align: center;\">
\t\t\t\t\t<div style=\"text-align: center;padding: 0px 0;\">
\t\t\t\t\t\t<h1 style=\"text-align: center;font-weight: 500;font-size:50px;margin-top:0px;\">
\t\t\t\t\t\t\t<i class=\"fa fa-code\"></i>
\t\t\t\t\t\t</h1>
\t\t\t\t\t\t<h3 style=\"text-align:center;\">Generate Referral Code</h3>

\t\t\t\t\t\t<h4>Create a referral code that you can share with anyone. Earn affiliate benefits when users register using your code.</h4>
\t\t\t\t\t\t<form role=\"form\" method=\"POST\" name=\"createReferralForm\" id=\"createReferralForm\" action=\"";
            // line 83
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("create-referral-code"), "html", null, true);
            yield "\">
\t\t\t\t\t\t\t<input type=\"hidden\" value=\"";
            // line 84
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["session"] ?? null), "userID", [], "any", false, false, false, 84), "html", null, true);
            yield "\" name=\"user_id\" id=\"user_id\">
\t\t\t\t\t\t\t<button class=\"button button-3d button-rounded button-small button-green  margin-top-20 margin-bottom-20\" type=\"submit\">
\t\t\t\t\t\t\t\t<i class=\"fa fa-arrow-circle-right\"></i>
\t\t\t\t\t\t\t\tGenerate Code Now</button>
\t\t\t\t\t\t</form>

\t\t\t\t\t</div>
\t\t\t\t</div>
\t\t\t</div>
\t\t</div>

\t</div>
\t<!-- End of Affiliate Content -->
";
        }
        // line 98
        yield "
";
        // line 99
        if ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "thisUser", [], "any", false, false, false, 99), "type", [], "any", false, false, false, 99) == "Åffiliate")) {
            // line 100
            yield "\t<!-- Start Affiliate Content -->
\t<div class=\"row\">
\t\t<div class=\"col-lg-12\">
\t\t\t<div class=\"bazicards\">
\t\t\t\t";
            // line 104
            if (CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "my_connections", [], "any", false, false, false, 104)) {
                // line 105
                yield "\t\t\t\t\t<div class=\"table table-responsive\" style=\"margin-top:20px;\">
\t\t\t\t\t\t<table id=\"datatable1\" class=\"table table-striped table-bordered\" cellspacing=\"0\" width=\"100%\">
\t\t\t\t\t\t\t<thead>
\t\t\t\t\t\t\t\t<tr>
\t\t\t\t\t\t\t\t\t<th>S.No.</th>
\t\t\t\t\t\t\t\t\t<th>Name</th>
\t\t\t\t\t\t\t\t\t<th>Referral Code</th>
\t\t\t\t\t\t\t\t\t<th>Date Joined</th>
\t\t\t\t\t\t\t\t</tr>
\t\t\t\t\t\t\t</thead>
\t\t\t\t\t\t\t<tfoot>
\t\t\t\t\t\t\t\t<tr>
\t\t\t\t\t\t\t\t\t<th>S.No.</th>
\t\t\t\t\t\t\t\t\t<th>Name</th>

\t\t\t\t\t\t\t\t\t<th>Referral Code</th>
\t\t\t\t\t\t\t\t\t<th>Date Joined</th>
\t\t\t\t\t\t\t\t</tr>
\t\t\t\t\t\t\t</tfoot>
\t\t\t\t\t\t\t<tbody>
\t\t\t\t\t\t\t\t";
                // line 125
                $context['_parent'] = $context;
                $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "my_connections", [], "any", false, false, false, 125));
                $context['loop'] = [
                  'parent' => $context['_parent'],
                  'index0' => 0,
                  'index'  => 1,
                  'first'  => true,
                ];
                if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof \Countable)) {
                    $length = count($context['_seq']);
                    $context['loop']['revindex0'] = $length - 1;
                    $context['loop']['revindex'] = $length;
                    $context['loop']['length'] = $length;
                    $context['loop']['last'] = 1 === $length;
                }
                foreach ($context['_seq'] as $context["_key"] => $context["row"]) {
                    // line 126
                    yield "\t\t\t\t\t\t\t\t\t<tr>
\t\t\t\t\t\t\t\t\t\t<td>";
                    // line 127
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 127) + 1), "html", null, true);
                    yield "</td>
\t\t\t\t\t\t\t\t\t\t<td>";
                    // line 128
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["row"], "first_name", [], "any", false, false, false, 128), "html", null, true);
                    yield "
\t\t\t\t\t\t\t\t\t\t\t";
                    // line 129
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["row"], "last_name", [], "any", false, false, false, 129), "html", null, true);
                    yield "</td>

\t\t\t\t\t\t\t\t\t\t<td>";
                    // line 131
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["row"], "referral_code", [], "any", false, false, false, 131), "html", null, true);
                    yield "</td>
\t\t\t\t\t\t\t\t\t\t<td>";
                    // line 132
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["row"], "date_created", [], "any", false, false, false, 132), "html", null, true);
                    yield "</td>
\t\t\t\t\t\t\t\t\t</tr>
\t\t\t\t\t\t\t\t";
                    ++$context['loop']['index0'];
                    ++$context['loop']['index'];
                    $context['loop']['first'] = false;
                    if (isset($context['loop']['revindex0'], $context['loop']['revindex'])) {
                        --$context['loop']['revindex0'];
                        --$context['loop']['revindex'];
                        $context['loop']['last'] = 0 === $context['loop']['revindex0'];
                    }
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_key'], $context['row'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 135
                yield "\t\t\t\t\t\t\t</tbody>
\t\t\t\t\t\t</table>
\t\t\t\t\t</div>

\t\t\t\t</div>
\t\t\t</div>
\t\t</div>
\t";
            }
            // line 143
            yield "
\t<div class=\"clear\"></div>
\t";
            // line 145
            if ((Twig\Extension\CoreExtension::length($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "my_referral_codes", [], "any", false, false, false, 145)) > 0)) {
                // line 146
                yield "\t\t<div class=\"row\" style=\"margin-top:10px;\">
\t\t\t<div class=\"col-lg-12 center\">
\t\t\t\t<div class=\"table-responsive\" style=\"margin-top:20px;\">
\t\t\t\t\t<table id=\"datatable1\" class=\"table table-striped table-bordered\" cellspacing=\"0\" width=\"100%\" style=\"background-color:#ffffff;\">
\t\t\t\t\t\t<thead>
\t\t\t\t\t\t\t<tr>
\t\t\t\t\t\t\t\t<th>CODE</th>
\t\t\t\t\t\t\t\t<th>Date Generated</th>
\t\t\t\t\t\t\t\t<th>Total Redeems</th>
\t\t\t\t\t\t\t\t<th>Share Code</th>
\t\t\t\t\t\t\t</tr>
\t\t\t\t\t\t</thead>
\t\t\t\t\t\t<tfoot>
\t\t\t\t\t\t\t<tr>
\t\t\t\t\t\t\t\t<th>CODE</th>
\t\t\t\t\t\t\t\t<th>Date Generated</th>
\t\t\t\t\t\t\t\t<th>Total Redeems</th>
\t\t\t\t\t\t\t\t<th>Share Code</th>
\t\t\t\t\t\t\t</tr>
\t\t\t\t\t\t</tfoot>
\t\t\t\t\t\t<tbody>
\t\t\t\t\t\t\t";
                // line 167
                $context['_parent'] = $context;
                $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "my_referral_codes", [], "any", false, false, false, 167));
                $context['loop'] = [
                  'parent' => $context['_parent'],
                  'index0' => 0,
                  'index'  => 1,
                  'first'  => true,
                ];
                if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof \Countable)) {
                    $length = count($context['_seq']);
                    $context['loop']['revindex0'] = $length - 1;
                    $context['loop']['revindex'] = $length;
                    $context['loop']['length'] = $length;
                    $context['loop']['last'] = 1 === $length;
                }
                foreach ($context['_seq'] as $context["_key"] => $context["coderow"]) {
                    // line 168
                    yield "\t\t\t\t\t\t\t\t";
                    yield from $this->loadTemplate("partials/widgets/referral-code-widget.twig", "dashboard.twig", 168)->unwrap()->yield($context);
                    // line 169
                    yield "\t\t\t\t\t\t\t";
                    ++$context['loop']['index0'];
                    ++$context['loop']['index'];
                    $context['loop']['first'] = false;
                    if (isset($context['loop']['revindex0'], $context['loop']['revindex'])) {
                        --$context['loop']['revindex0'];
                        --$context['loop']['revindex'];
                        $context['loop']['last'] = 0 === $context['loop']['revindex0'];
                    }
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_key'], $context['coderow'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 170
                yield "

\t\t\t\t\t\t</tbody>
\t\t\t\t\t</table>
\t\t\t\t\t<p>Note: Copy the code to share with someone you refer. You can also share your code on social networks and whatsApp group.
\t\t\t\t\t</p>
\t\t\t\t</div>
\t\t\t";
            }
            // line 178
            yield "
\t\t</div>
\t</div>
</div>";
        }
        // line 181
        yield "<!-- End of Affiliate Content --><!-- Content --><div
class=\"row\">

<!-- Item -->
<div class=\"col-lg-3 col-md-6\">
\t<a href=\"";
        // line 186
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/saved-reads\">
\t\t<div class=\"dashboard-stat color-bazi custTour1\">
\t\t\t<div class=\"dashboard-stat-content\">
\t\t\t\t<h4 style=\"color:#ffffff;\">";
        // line 189
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "num_saves", [], "any", false, false, false, 189), "html", null, true);
        yield "
\t\t\t\t</h4>
\t\t\t\t<span style=\"color:#ffffff;\">My Saved Reads</span>
\t\t\t</div>
\t\t\t<div class=\"dashboard-stat-icon\">
\t\t\t\t<i style=\"color:#ffffff;\" class=\"im im-icon-Data-Cloud\"></i>
\t\t\t</div>
\t\t</div>
\t</a>
</div>

<!-- Item -->
<div class=\"col-lg-3 col-md-6\">
\t<a href=\"";
        // line 202
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/manage-reviews\">
\t\t<div class=\"dashboard-stat color-bazi custTour2\">
\t\t\t<div class=\"dashboard-stat-content\">
\t\t\t\t<h4 style=\"color:#ffffff;\">";
        // line 205
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "num_reviews", [], "any", false, false, false, 205), "html", null, true);
        yield "
\t\t\t\t</h4>
\t\t\t\t<span style=\"color:#ffffff;\">My Reviews</span>
\t\t\t</div>
\t\t\t<div class=\"dashboard-stat-icon\">
\t\t\t\t<i style=\"color:#ffffff;\" class=\"sl sl-icon-speech\"></i>
\t\t\t</div>
\t\t</div>
\t</a>
</div>

<!-- Item -->
<div class=\"col-lg-3 col-md-6\">
\t<a href=\"";
        // line 218
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/bookmarks\">
\t\t<div class=\"dashboard-stat color-bazi custTour3\">
\t\t\t<div class=\"dashboard-stat-content\">
\t\t\t\t<h4>";
        // line 221
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "num_likes", [], "any", false, false, false, 221), "html", null, true);
        yield "
\t\t\t\t</h4>
\t\t\t\t<span style=\"color:#ffffff;\">My Favourites</span>
\t\t\t</div>
\t\t\t<div class=\"dashboard-stat-icon\">
\t\t\t\t<i style=\"color:#ffffff;\" class=\"im im-icon-Love-User\"></i>
\t\t\t</div>
\t\t</div>
\t</a>
</div>
<!-- Item -->
<div class=\"col-lg-3 col-md-6\">
\t<a href=\"";
        // line 233
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/reward-points\">
\t\t<div class=\"dashboard-stat color-bazi custTour4\">
\t\t\t<div class=\"dashboard-stat-content\">
\t\t\t\t<h4>";
        // line 236
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "reward_points", [], "any", false, false, false, 236), "html", null, true);
        yield "
\t\t\t\t</h4>
\t\t\t\t<span style=\"color:#ffffff;\">My Reward Points</span>
\t\t\t</div>
\t\t\t<div class=\"dashboard-stat-icon\">
\t\t\t\t<i class=\"im im-icon-Dollar\"></i>
\t\t\t</div>
\t\t</div>
\t</a>
</div></div><!-- MemberCard --><div class=\"row\">
";
        // line 246
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "membership_info", [], "any", false, false, false, 246)) {
            // line 247
            yield "\t<div class=\"boxed-widget margin-top-35 margin-bottom-35\">
\t\t<div class=\"hosted-by-title\">
\t\t\t<h4>
\t\t\t\t<a href=\"#\">";
            // line 250
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["session"] ?? null), "first_name", [], "any", false, false, false, 250), "html", null, true);
            yield "
\t\t\t\t\t";
            // line 251
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["session"] ?? null), "last_name", [], "any", false, false, false, 251), "html", null, true);
            yield "</a>
\t\t\t\t<span>Premium Member</span>
\t\t\t</h4>
\t\t\t<a href=\"";
            // line 254
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
            yield "/profile\" class=\"hosted-by-avatar\"><img src=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
            yield "/images/avatar.jpg\" alt=\"\"></a>
\t\t</div>
\t\t<ul class=\"listing-details-sidebar\">
\t\t\t<li>
\t\t\t\t<i class=\"sl sl-icon-present\"></i>
\t\t\t\t";
            // line 259
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "reward_points", [], "any", false, false, false, 259), "html", null, true);
            yield "
\t\t\t\tReward Points</li>
\t\t\t<li>
\t\t\t\t<i class=\"sl sl-icon-like\"></i>
\t\t\t\tExpiring
\t\t\t\t";
            // line 264
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "membership_info", [], "any", false, false, false, 264), "date_expiring", [], "any", false, false, false, 264), "html", null, true);
            yield "</li>
\t\t\t<li>
\t\t\t\t<i class=\"sl sl-icon-badge\"></i>
\t\t\t\t";
            // line 267
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "membership_info", [], "any", false, false, false, 267), "plan_name", [], "any", false, false, false, 267), "html", null, true);
            yield "
\t\t\t\tMember</li>
\t\t</ul>

\t\t<!-- Reply to review popup -->
\t\t<div id=\"join-bazichic\" class=\"zoom-anim-dialog mfp-hide\" style=\"max-width: 100%;\">
\t\t\t<div class=\"small-dialog-header\">
\t\t\t\t<h3>New to BaziChic?</h3>
\t\t\t</div>
\t\t\t<div class=\"message-reply margin-top-0\">
\t\t\t\t";
            // line 277
            yield from $this->loadTemplate("forms/register_form.twig", "dashboard.twig", 277)->unwrap()->yield($context);
            // line 278
            yield "\t\t\t</div>
\t\t</div>

\t</div>
";
        }
        // line 282
        yield "</div><!-- MemberCard / End--><div
class=\"row\">
<!-- ########## Recent Activity #############-->
";
        // line 285
        if ((Twig\Extension\CoreExtension::length($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "recent_activities", [], "any", false, false, false, 285)) > 0)) {
            // line 286
            yield "\t<div class=\"col-lg-6 col-md-12\">
\t\t<div class=\"dashboard-list-box with-icons margin-top-20\">
\t\t\t<h4>Recent Activities</h4>
\t\t\t<ul>
\t\t\t\t";
            // line 290
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "recent_activities", [], "any", false, false, false, 290));
            foreach ($context['_seq'] as $context["_key"] => $context["activity"]) {
                // line 291
                yield "\t\t\t\t\t<li>
\t\t\t\t\t\t<i class=\"list-box-icon sl sl-icon-clock\"></i>
\t\t\t\t\t\t<strong>";
                // line 293
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["activity"], "message", [], "any", false, false, false, 293), "html", null, true);
                yield "</strong>
\t\t\t\t\t\t<a href=\"";
                // line 294
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["activity"], "action_link", [], "any", false, false, false, 294), "html", null, true);
                yield "\" class=\"close-list-item\">
\t\t\t\t\t\t\t<i class=\"fa fa-close\"></i>
\t\t\t\t\t\t</a>
\t\t\t\t\t</li>

\t\t\t\t";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_key'], $context['activity'], $context['_parent']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 300
            yield "\t\t\t</ul>
\t\t\t<a href=\"";
            // line 301
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
            yield "/timeline\" class=\"button\" style=\"margin-top:12px;margin-bottom:12px;margin-right:8px;float:right;\">
\t\t\t\t<i class=\"fa fa-arrow-right\"></i>
\t\t\t\tView All Activities</a>
\t\t</div>
\t</div>
";
        }
        // line 307
        yield "<!-- ########## Recent Activity #############-->


<!-- ########## Recent Activity #############-->
";
        // line 311
        if ((Twig\Extension\CoreExtension::length($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "dash_notis", [], "any", false, false, false, 311)) > 0)) {
            // line 312
            yield "\t<div class=\"col-lg-6 col-md-12\">
\t\t<div class=\"dashboard-list-box with-icons margin-top-20\">
\t\t\t<h4>My Notifications</h4>
\t\t\t<ul>
\t\t\t\t";
            // line 316
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "dash_notis", [], "any", false, false, false, 316));
            foreach ($context['_seq'] as $context["_key"] => $context["notification"]) {
                // line 317
                yield "\t\t\t\t\t<li>
\t\t\t\t\t\t<i class=\"list-box-icon sl sl-icon-bell\"></i>
\t\t\t\t\t\t<strong>";
                // line 319
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["notification"], "message", [], "any", false, false, false, 319), "html", null, true);
                yield "</strong>
\t\t\t\t\t\t<a href=\"";
                // line 320
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("read_notification", ["id" => CoreExtension::getAttribute($this->env, $this->source, $context["notification"], "id", [], "any", false, false, false, 320)]), "html", null, true);
                yield "\" class=\"close-list-item\">
\t\t\t\t\t\t\t<i class=\"fa fa-close\"></i>
\t\t\t\t\t\t</a>
\t\t\t\t\t</li>

\t\t\t\t";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_key'], $context['notification'], $context['_parent']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 326
            yield "\t\t\t</ul>
\t\t\t<a href=\"";
            // line 327
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("my_notifications"), "html", null, true);
            yield "\" class=\"button\" style=\"margin-top:12px;margin-bottom:12px;margin-right:8px;float:right;\">
\t\t\t\t<i class=\"fa fa-arrow-right\"></i>
\t\t\t\tView All Notifications</a>
\t\t</div>
\t</div>
";
        }
        // line 333
        yield "<!-- ########## Recent Activity #############-->


<!-- ########## Document Saves #############-->
";
        // line 337
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "saved_docs", [], "any", false, false, false, 337)) {
            // line 338
            yield "\t<div class=\"col-lg-12 col-md-12\">
\t\t<div class=\"dashboard-list-box invoices with-icons margin-top-20\">
\t\t\t<h4>Recently Reading</h4>
\t\t\t<ul>
\t\t\t\t";
            // line 342
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "saved_docs", [], "any", false, false, false, 342));
            foreach ($context['_seq'] as $context["_key"] => $context["row"]) {
                // line 343
                yield "\t\t\t\t\t<li>
\t\t\t\t\t\t<i class=\"list-box-icon im im-icon-Arrow-LeftinCircle\"></i>
\t\t\t\t\t\t<strong>";
                // line 345
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["row"], "title", [], "any", false, false, false, 345), "html", null, true);
                yield "</strong>
\t\t\t\t\t\t<ul>
\t\t\t\t\t\t\t<li class=\"unpaid\">Started
\t\t\t\t\t\t\t\t";
                // line 348
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["row"], "date_created", [], "any", false, false, false, 348), "html", null, true);
                yield "</li>
\t\t\t\t\t\t\t<li>";
                // line 349
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["row"], "read_status", [], "any", false, false, false, 349), "html", null, true);
                yield "</li>
\t\t\t\t\t\t\t";
                // line 350
                if (CoreExtension::getAttribute($this->env, $this->source, ($context["document"] ?? null), "date_updated", [], "any", false, false, false, 350)) {
                    // line 351
                    yield "\t\t\t\t\t\t\t\t<li>Last Read:
\t\t\t\t\t\t\t\t\t";
                    // line 352
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["row"], "date_updated", [], "any", false, false, false, 352), "html", null, true);
                    yield "</li>
\t\t\t\t\t\t\t";
                }
                // line 354
                yield "\t\t\t\t\t\t\t";
                if (CoreExtension::getAttribute($this->env, $this->source, ($context["document"] ?? null), "qcode", [], "any", false, false, false, 354)) {
                    // line 355
                    yield "\t\t\t\t\t\t\t\t<li>
\t\t\t\t\t\t\t\t\t<a href=\"";
                    // line 356
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
                    yield "/book-detail/";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["document"] ?? null), "qcode", [], "any", false, false, false, 356), "html", null, true);
                    yield "\">
\t\t\t\t\t\t\t\t\t\tView Detail</a>
\t\t\t\t\t\t\t\t</li>
\t\t\t\t\t\t\t";
                }
                // line 360
                yield "\t\t\t\t\t\t</ul>
\t\t\t\t\t\t<div class=\"buttons-to-right\">

\t\t\t\t\t\t\t<form action=\"";
                // line 363
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("ebook-reader"), "html", null, true);
                yield "\" name=\"launchReaderForm\" id=\"launchReaderForm\" method=\"POST\" style=\"margin-top:10px;margin-bottom:10px;\">
\t\t\t\t\t\t\t\t<input type=\"hidden\" name=\"doc_id\" value=\"";
                // line 364
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["row"], "id", [], "any", false, false, false, 364), "html", null, true);
                yield "\">
\t\t\t\t\t\t\t\t<input type=\"hidden\" name=\"doc_link\" value=\"";
                // line 365
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["row"], "link", [], "any", false, false, false, 365), "html", null, true);
                yield "\">
\t\t\t\t\t\t\t\t<input type=\"hidden\" name=\"is_downloadable\" value=\"";
                // line 366
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["row"], "is_downloadable", [], "any", false, false, false, 366), "html", null, true);
                yield "\">
\t\t\t\t\t\t\t\t<input type=\"hidden\" name=\"last_read_page\" value=\"";
                // line 367
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["row"], "page", [], "any", false, false, false, 367), "html", null, true);
                yield "\">
\t\t\t\t\t\t\t\t<button type=\"submit\" class=\"button btn btn-purple text-white\">
\t\t\t\t\t\t\t\t\t<i class=\"fa fa-arrow-right\"></i>
\t\t\t\t\t\t\t\t\t";
                // line 370
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["row"], "access_verb", [], "any", false, false, false, 370), "html", null, true);
                yield "
\t\t\t\t\t\t\t\t\tNOW</button>
\t\t\t\t\t\t\t</form>

\t\t\t\t\t\t\t<input type=\"hidden\" name=\"userRole\" value=\"";
                // line 374
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["paeg"] ?? null), "thisUser", [], "any", false, false, false, 374), "type", [], "any", false, false, false, 374), "html", null, true);
                yield "\">

\t\t\t\t\t\t</div>
\t\t\t\t\t</li>
\t\t\t\t";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_key'], $context['row'], $context['_parent']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 379
            yield "
\t\t\t</ul>
\t\t</div>
\t</div>
";
        }
        // line 384
        yield "<!-- ########## Document Saves #############--></div>";
        yield from [];
    }

    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_jsfooter(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        yield "<link rel=\"stylesheet\" href=\"";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/scripts/shepherd.js/dist/css/shepherd.css\"/><script src=\"";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/scripts/shepherd.js/dist/js/shepherd.js\"></script><script src=\"";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/scripts/tour.js\"></script><script type=\"text/javascript\">
function showAffiliateTour(first_name) {
// alert(\"showAffiliateTour\");
// const first_name = 'Hello ';
const tour = new Shepherd.Tour({
defaultStepOptions: {
cancelIcon: {
enabled: true
},
classes: 'class-1 class-2',
scrollTo: {
behavior: 'smooth',
block: 'center'
}
}
});

tour.addStep({
title: 'LET US START',
text: 'Hi ' + first_name + '! First generate your referral code that you can share with anyone. You can optionally generate multiple referral codes to track your conversions based on an audience or event.',
attachTo: {
element: '.firstTour',
on: 'bottom'
},
buttons: [
{
action() {
return this.next();
},
text: 'Next'
}
],
id: 'creating'
});

tour.addStep({
title: 'MAKE CONNECTIONS',
text: 'Earn affiliate benefits when your connections grow. Update your paypal account email in profile page to redeem your earnings.',
attachTo: {
element: '.secondTour',
on: 'bottom'
},
buttons: [
{
action() {
return this.back();
},
classes: 'shepherd-button-secondary',
text: 'Back'
}, {
action() {
return this.complete();
},
text: 'Finish'
}
],
id: 'creating2'
});


tour.start();
console.log('tour started');
}


\$(document).ready(function () {

console.log('document is ready');
var thisUserType = '";
        // line 452
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "thisUser", [], "any", false, false, false, 452), "type", [], "any", false, false, false, 452), "html", null, true);
        yield "';
var first_name = '";
        // line 453
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "thisUser", [], "any", false, false, false, 453), "first_name", [], "any", false, false, false, 453), "html", null, true);
        yield "';
console.log(first_name + ' ! document is ready with ' + thisUserType);

\$(\"#startTourBtn\").click(function () {
// alert(this.id);
// showAffiliateTour(first_name);
if (thisUserType == 'Åffiliate') {
showAffiliateTour(first_name);
} else {
showCustomerTour(first_name);
}
});

});</script>";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "dashboard.twig";
    }

    /**
     * @codeCoverageIgnore
     */
    public function isTraitable(): bool
    {
        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo(): array
    {
        return array (  818 => 453,  814 => 452,  728 => 384,  721 => 379,  710 => 374,  703 => 370,  697 => 367,  693 => 366,  689 => 365,  685 => 364,  681 => 363,  676 => 360,  667 => 356,  664 => 355,  661 => 354,  656 => 352,  653 => 351,  651 => 350,  647 => 349,  643 => 348,  637 => 345,  633 => 343,  629 => 342,  623 => 338,  621 => 337,  615 => 333,  606 => 327,  603 => 326,  591 => 320,  587 => 319,  583 => 317,  579 => 316,  573 => 312,  571 => 311,  565 => 307,  556 => 301,  553 => 300,  541 => 294,  537 => 293,  533 => 291,  529 => 290,  523 => 286,  521 => 285,  516 => 282,  509 => 278,  507 => 277,  494 => 267,  488 => 264,  480 => 259,  470 => 254,  464 => 251,  460 => 250,  455 => 247,  453 => 246,  440 => 236,  434 => 233,  419 => 221,  413 => 218,  397 => 205,  391 => 202,  375 => 189,  369 => 186,  362 => 181,  356 => 178,  346 => 170,  332 => 169,  329 => 168,  312 => 167,  289 => 146,  287 => 145,  283 => 143,  273 => 135,  256 => 132,  252 => 131,  247 => 129,  243 => 128,  239 => 127,  236 => 126,  219 => 125,  197 => 105,  195 => 104,  189 => 100,  187 => 99,  184 => 98,  167 => 84,  163 => 83,  138 => 61,  120 => 45,  118 => 44,  115 => 43,  105 => 36,  93 => 26,  91 => 25,  87 => 23,  76 => 15,  64 => 5,  62 => 4,  59 => 3,  52 => 2,  41 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "dashboard.twig", "C:\\xampp\\htdocs\\bazichic\\resources\\Views\\dashboard.twig");
    }
}
