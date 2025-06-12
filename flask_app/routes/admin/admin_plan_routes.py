from flask import Blueprint, render_template

admin_plan_bp = Blueprint('admin_plan', __name__, url_prefix='/admin/plans', template_folder='../../../../templates/admin/plans')

@admin_plan_bp.route('/manage', methods=['GET'])
def manage_plans_get():
    return "Admin Plans: Manage Plans (GET)"

@admin_plan_bp.route('/edit/<int:plan_id>', methods=['GET'])
def edit_plan_get(plan_id):
    return f"Admin Plans: Edit Plan {plan_id} form (GET)"

@admin_plan_bp.route('/update/<int:plan_id>', methods=['POST'])
def update_plan_post(plan_id):
    return f"Admin Plans: Update Plan {plan_id} (POST)"

# This was originally /admin/free-trials-summary, linking to AdminController::FreeTrialsSummary
# Placing it under plans as it's related to plans having trials.
@admin_plan_bp.route('/free-trials-summary', methods=['GET'])
def free_trials_summary_get():
    return "Admin Plans: Free Trials Summary (GET)"

# Placeholder for /apis/free_trials/grant from PHP admin routes
@admin_plan_bp.route('/grant-free-trial', methods=['POST']) # Changed to be more RESTful
def grant_free_trial_post():
    return "Admin Plans: Grant Free Trial (POST)"
