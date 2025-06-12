from flask_app.extensions import db

class SiteSetting(db.Model):
    __tablename__ = 'site_settings'

    id = db.Column(db.Integer, primary_key=True, autoincrement=True)
    name = db.Column(db.String(100), unique=True, nullable=False) # e.g., "default_settings", "main_config"

    admin_email = db.Column(db.String(255), nullable=False)
    maintenance_on = db.Column(db.Boolean, default=False, nullable=False)
    banner_link = db.Column(db.String(500), nullable=True)

    # As per migration, no site_email, phone, or timestamps.

    def __repr__(self):
        return f'<SiteSetting {self.name}>'

    @classmethod
    def get_current_settings(cls, session=None):
        """
        Helper method to get the primary site settings row.
        Assumes there's one primary row, typically with id=1 or a specific name.
        """
        db_session = session or db.session
        # Adjust to query by a specific known ID or name if that's the convention
        # For now, fetches the first record found, or creates a default if none.
        settings = db_session.query(cls).first()
        if not settings:
            # This part is tricky: creating default settings might belong in app setup/migrations.
            # For now, let's assume it should exist.
            # Or, one could create a default here if appropriate for the application.
            # For example:
            # settings = cls(name="default", admin_email="admin@example.com")
            # db_session.add(settings)
            # db_session.commit()
            # print("WARN: No site settings found, created a default placeholder. Please review.")
            # raise ValueError("Site settings not found. Please initialize them.")
            pass # Or return None / raise error, depending on desired app behavior
        return settings
