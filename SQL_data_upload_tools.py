import requests
import pandas as pd
from sklearn.model_selection import train_test_split
from sklearn.tree import DecisionTreeClassifier


class SQL_data_upload_tools:

    @staticmethod
    def get_lat_long(town: str):
        api_key = "AIzaSyBabJdeea-CKEsn-AbqQcOglvu81b95wO8"
        base_url = "https://maps.googleapis.com/maps/api/geocode/json"
        address = f"{town}, Singapore"
        params = {"address": address, "key": api_key}

        response = requests.get(base_url, params=params)
        data = response.json()

        if data["status"] == "OK":
            location = data["results"][0]["geometry"]["location"]
            return location["lat"], location["lng"]
        else:
            return {"error": "Unable to fetch coordinates. Please check the town name."}

    # function purpose : Set dataframe. However do configure it when possible.
    @staticmethod
    def File_insert(path_of_file: str):
        file_dataframe = pd.read_excel(path_of_file)
        return file_dataframe

    @staticmethod
    def d_tree_ID_LOC(Town_name: str):

        #step1 , obtain data from db CHANGE  #TRISTIAN , CHANGE FILE VAR TO GET THE PANDAS DATA FRAME FROM SQL
        Data_base_df = SQL_data_upload_tools.File_insert(
            r"C:\Users\User\Downloads\clustering_results_High_defined_clustered.xlsx")
        #step2 , prepare columns
        dpt_col = Data_base_df[['LOC cluster']]

        indpt_col = Data_base_df[['Latitude', 'Longitude']]

        indpt_train, indpt_test, dpt_train, dpt_test = train_test_split(indpt_col, dpt_col,
                                                                        train_size=0.8,
                                                                        random_state=1234)

        regressor_tree = DecisionTreeClassifier(random_state=1234)

        #train our model

        path = regressor_tree.cost_complexity_pruning_path(indpt_train, dpt_train)

        #prune the overfitting tree
        Cost_cplxty_pruning_alpha_list = path.ccp_alphas  # ccp_alphas is a code function

        Cost_cplxty_pruning_alpha_list = Cost_cplxty_pruning_alpha_list[:-1]
        print(Cost_cplxty_pruning_alpha_list)

        train_scores, test_scores = [], []

        for alpha in Cost_cplxty_pruning_alpha_list:
            regressor_tree_seed_with_alpha = DecisionTreeClassifier(random_state=1234, ccp_alpha=alpha)
            model = regressor_tree_seed_with_alpha.fit(indpt_train, dpt_train)
            train_scores.append(model.score(indpt_train, dpt_train))
            test_scores.append(model.score(indpt_test, dpt_test))

        Test_score_index_for_best_alpha = test_scores.index(max(test_scores))

        best_alpha = Cost_cplxty_pruning_alpha_list[Test_score_index_for_best_alpha]

        #Found optimal value , now prune and re-create treee :D
        regressor_tree_seed_with_alpha = DecisionTreeClassifier(random_state=1234, ccp_alpha=best_alpha)
        Loc_model = regressor_tree_seed_with_alpha.fit(indpt_train, dpt_train)

        #Found the lat and the long
        curr_lat, Curr_long = SQL_data_upload_tools.get_lat_long(Town_name)

        SQL_data_upload_tools.get_lat_long(Town_name)

        # predict new data
        new_data = [[curr_lat, Curr_long]]

        # Making predictions
        predictions = Loc_model.predict(new_data)

        print(list(indpt_train))

        print("Predictions:", predictions)

        return predictions

    @staticmethod
    def get_hobbies(Curr_user_df):

        # Step 1: Load Data  #TRISTIAN , CHANGE FILE VAR TO GET THE PANDAS DATA FRAME FROM SQL
        curr_db_DF = SQL_data_upload_tools.File_insert(
            r"C:\Users\User\Downloads\clustering_results_High_defined_clustered.xlsx")

        # Step 2: Filter to only users who prefer hobbies interest
        curr_db_DF = curr_db_DF.loc[curr_db_DF["Preference"] == "Hobbies interest"]

        # Step 3: Prepare Independent and Dependent Variables
        dpt_col = curr_db_DF[['groupings_cluster']]  # Target variable
        indpt_col = curr_db_DF[['General Hobbies 1', 'General Hobbies 2']]  # Features

        indpt_col = pd.get_dummies(indpt_col)

        # Split dataset
        indpt_train, indpt_test, dpt_train, dpt_test = train_test_split(
            indpt_col, dpt_col, train_size=0.8, random_state=1234)

        # Step 4: Train Decision Tree Model
        regressor_tree = DecisionTreeClassifier(random_state=1234)
        regressor_tree.fit(indpt_train, dpt_train)

        # Step 5: Prune Overfitting Tree
        path = regressor_tree.cost_complexity_pruning_path(indpt_train, dpt_train)
        ccp_alphas = path.ccp_alphas[:-1]

        train_scores, test_scores = [], []
        for alpha in ccp_alphas:
            pruned_tree = DecisionTreeClassifier(random_state=1234, ccp_alpha=alpha)
            pruned_tree.fit(indpt_train, dpt_train)
            train_scores.append(pruned_tree.score(indpt_train, dpt_train))
            test_scores.append(pruned_tree.score(indpt_test, dpt_test))

        best_alpha_index = test_scores.index(max(test_scores))
        best_alpha = ccp_alphas[best_alpha_index]

        # Step 6: Train Final Pruned Model
        final_tree = DecisionTreeClassifier(random_state=1234, ccp_alpha=best_alpha)
        final_tree.fit(indpt_train, dpt_train)

        # Step 7: Prepare New User Data for Prediction
        new_data = Curr_user_df[["General Hobbies 1", "General Hobbies 2"]]
        new_data = pd.get_dummies(new_data)

        # Ensure new_data has the same columns as training data
        new_data = new_data.reindex(columns=indpt_train.columns, fill_value=0)

        # Step 8: Predict Groupings Cluster
        predictions = final_tree.predict(new_data)

        print("Predictions:", predictions)

        return predictions

    @staticmethod
    def get_Learn(Curr_user_df):

        # Step 1: Load Data  #TRISTIAN , CHANGE FILE VAR TO GET THE PANDAS DATA FRAME FROM SQL
        curr_db_DF = SQL_data_upload_tools.File_insert(
            r"C:\Users\User\Downloads\clustering_results_High_defined_clustered.xlsx")

        # Step 2: Filter to only users who prefer hobbies interest
        curr_db_DF = curr_db_DF.loc[curr_db_DF["Preference"] == "Overall learning capability"]

        # Step 3: Prepare Independent and Dependent Variables
        dpt_col = curr_db_DF[['groupings_cluster']]  # Target variable
        indpt_col = curr_db_DF.loc[:, 'SQL': 'Rust']  # Features


        # Split dataset
        indpt_train, indpt_test, dpt_train, dpt_test = train_test_split(
            indpt_col, dpt_col, train_size=0.8, random_state=1234)

        # Step 4: Train Decision Tree Model
        regressor_tree = DecisionTreeClassifier(random_state=1234)
        regressor_tree.fit(indpt_train, dpt_train)

        # Step 5: Prune Overfitting Tree
        path = regressor_tree.cost_complexity_pruning_path(indpt_train, dpt_train)
        ccp_alphas = path.ccp_alphas[:-1]

        train_scores, test_scores = [], []
        for alpha in ccp_alphas:
            pruned_tree = DecisionTreeClassifier(random_state=1234, ccp_alpha=alpha)
            pruned_tree.fit(indpt_train, dpt_train)
            train_scores.append(pruned_tree.score(indpt_train, dpt_train))
            test_scores.append(pruned_tree.score(indpt_test, dpt_test))

        best_alpha_index = test_scores.index(max(test_scores))
        best_alpha = ccp_alphas[best_alpha_index]

        # Step 6: Train Final Pruned Model
        final_tree = DecisionTreeClassifier(random_state=1234, ccp_alpha=best_alpha)
        final_tree.fit(indpt_train, dpt_train)

        # Step 7: Prepare New User Data for Prediction
        new_data = Curr_user_df.loc[:, 'SQL': 'Rust']


        # Ensure new_data has the same columns as training data
        new_data = new_data.reindex(columns=indpt_train.columns, fill_value=0)

        # Step 8: Predict Groupings Cluster
        predictions = final_tree.predict(new_data)

        print("Predictions:", predictions)

        return predictions

    @staticmethod
    def get_industry(Curr_user_df):

        # Step 1: Load Data  #TRISTIAN , CHANGE FILE VAR TO GET THE PANDAS DATA FRAME FROM SQL
        curr_db_DF = SQL_data_upload_tools.File_insert(
            r"C:\Users\User\Downloads\clustering_results_High_defined_clustered.xlsx")

        # Step 2: Filter to only users who prefer hobbies interest
        curr_db_DF = curr_db_DF.loc[curr_db_DF["Preference"] == "Industry interest"]

        # Step 3: Prepare Independent and Dependent Variables
        User_specs = curr_db_DF.loc[0,"Specialization"]

        curr_db_DF = curr_db_DF.loc[curr_db_DF["Specialization"] == User_specs]

        return curr_db_DF["groupings_cluster"].value_counts().idxmin()





    # recommend the 3 groups
    @staticmethod
    def user_recc():
        #Step 1 : insert CSV TRISTIAN , CHANGE FILE VAR TO ACCEPT CSV
        file_dataframe = pd.read_csv(r"C:\Users\User\Downloads\User_ID,Specialization,SQL,Java Scr.txt")
        print(file_dataframe)

        #Step 2 : file_dataframe["Location"] -> throw it into the tree modeling
        file_dataframe["LOC cluster"] = SQL_data_upload_tools.d_tree_ID_LOC(file_dataframe["Location"])

        #step 3 : need. get , the hobbies
        hobbies_recommendation = SQL_data_upload_tools.get_hobbies(file_dataframe)

        #step 4 : need. get , industry
        industry_recommendation = SQL_data_upload_tools.get_Learn(file_dataframe)

        # step 5 : need. get ,
        industry_recommendation = SQL_data_upload_tools.get_industry(file_dataframe)

        return hobbies_recommendation,industry_recommendation,industry_recommendation

        #returns the predicted clusters

if __name__ == "__main__":
    town_name = "Hougang"
    coordinates = SQL_data_upload_tools.d_tree_ID_LOC(town_name)
    SQL_data_upload_tools.user_recc()
