import sys
import pandas as pd
from kmodes.kmodes import KModes
import numpy as np
from kneed import KneeLocator
import os
from sklearn.cluster import KMeans

if len(sys.argv) != 4:
    print("Please enter the correct number of arguments")
    sys.exit(1)

# Get the arguments
FILE_PATH = sys.argv[1]
MIN_GROUP_SIZE = int(sys.argv[2])
MAX_GROUP_SIZE = int(sys.argv[3])
AVG_GROUP_SIZE = (MIN_GROUP_SIZE + MAX_GROUP_SIZE) // 2
class Data_algorithm_creation():

    # function purpose : Set dataframe. However do configure it when possible.
    @staticmethod
    def File_insert():
        file_dataframe = pd.read_excel(r"$file_path")
        return file_dataframe

    # Function : performing elbow method
    @staticmethod
    def Elbow_score(File_var_given):
        # Process : extract out every ID column, then remove id
        LatLong_cols = File_var_given.loc[:, ['Latitude', 'Longitude']]

        print(LatLong_cols)

        inertia_score = []
        for k_count in range(2, 11):
            k_m_cluster = KMeans(
                n_clusters=k_count,
                n_init=25,
                random_state=1234)

            k_m_cluster.fit(LatLong_cols)

            #Add to array , round to 3dp
            inertia_score.append(round(k_m_cluster.inertia_, 3))

        return [2, 3, 4, 5, 6, 7, 8, 9, 10], inertia_score

    @staticmethod
    def find_optimal_elbow(k_values, inertia_list):
        # Check if trend increasing or decreasing
        if inertia_list[0] < inertia_list[1]:
            Increase_or_decrease = "increasing"
        else:
            Increase_or_decrease = "decreasing"

        #difference between each result
        difference_between_results = np.diff(inertia_list)

        #Inertia rate change
        Inertia_rate_change = np.diff(difference_between_results)  # Second derivative

        #determine the curve
        if np.mean(Inertia_rate_change) > 0:
            curve_of_graph = "convex"
        else:
            curve_of_graph = "concave"

        # Apply KneeLocator with detected curve and direction
        knee_locator = KneeLocator(
            k_values,
            inertia_list,
            curve=curve_of_graph,
            direction=Increase_or_decrease
        )

        optimal_Number_of_clusters = knee_locator.knee

        if optimal_Number_of_clusters is None:
            print(" KneeLocator couldn't find a knee point. bring last K")
            optimal_Number_of_clusters = k_values[-1]

        print("Elbow cluster methods : ")
        print(optimal_Number_of_clusters)
        return optimal_Number_of_clusters

    @staticmethod
    def return_elbow_cluster_Label(File_var_given, cluster_numb):

        LatLong_cols = File_var_given.loc[:, ['Latitude', 'Longitude']]

        # Create Calinski seed
        # ------------------------------------------------------------------------------------------
        k_m_cluster = KMeans(
            n_clusters=cluster_numb,
            n_init=25,
            random_state=1234)
        k_m_cluster.fit(LatLong_cols)

        # Get cluster labels
        return k_m_cluster.labels_

    #Profession -> Method , regular filtering
    @staticmethod
    def industry_interest(clustered_LOC_file_pref):

        # Initialize a variable to keep track of unique cluster ID across specializations
        cluster_id = 1
        processed_dfs = []  # List to store processed dataframes

        clustered_LOC_file_pref["groupings_cluster"] = "0"

        # Step 1: Break down dataframe by Specialization
        specializations = clustered_LOC_file_pref["Specialization"].unique()

        for specialization in specializations:
            # Filter dataframe for current specialization
            df_specialization = clustered_LOC_file_pref[
                clustered_LOC_file_pref["Specialization"] == specialization].copy()

            # Assign cluster IDs in groups of MIN_GROUP_SIZE
            count = 0  # Counter for number of people in the current cluster
            for index in df_specialization.index:
                df_specialization.at[index, "groupings_cluster"] = cluster_id
                count += 1

                # Once MIN_GROUP_SIZE people are assigned, increment the cluster ID
                if count == MIN_GROUP_SIZE:
                    cluster_id += 1
                    count = 0

            # Store processed dataframe
            processed_dfs.append(df_specialization)

        # Step 2: Merge all processed dataframes back together
        clustered_LOC_file_pref = pd.concat(processed_dfs, ignore_index=True)

        # Print Final Cluster Sizes

        clustered_LOC_file_pref["groupings_cluster"] = (
                clustered_LOC_file_pref["LOC cluster"].astype(str) + "_" +
                clustered_LOC_file_pref["Preference"].astype(str) + "_" +
                clustered_LOC_file_pref["groupings_cluster"].astype(str)
        )

        print(clustered_LOC_file_pref)

        return clustered_LOC_file_pref

    # Example usage:
    # df = industry_interest(clustered_LOC_file_pref)
    # print(df["groupings_cluster"].value_counts())  # Check cluster assignments

    # Let me know if you need any modifications! 😊

    #learning -> method , forced K means
    @staticmethod
    def learning_interest(clustered_LOC_file_pref):
        # Step 1: Define Number of Clusters Based on MIN_GROUP_SIZE and MAX_GROUP_SIZE
        num_clusters = len(clustered_LOC_file_pref) // AVG_GROUP_SIZE

        # Step 2: Apply K-Means (Fixed Number of Clusters)
        kmeans = KMeans(n_clusters=num_clusters, random_state=42, n_init=10)
        clustered_LOC_file_pref["groupings_cluster"] = kmeans.fit_predict(clustered_LOC_file_pref.loc[:, "SQL":"Rust"])

        # Step 3: Check Cluster Sizes
        cluster_sizes = clustered_LOC_file_pref["groupings_cluster"].value_counts()

        # Step 4: If Any Cluster < MIN_GROUP_SIZE, Merge to Closest Cluster
        for cluster_id, size in cluster_sizes.items():
            if size < MIN_GROUP_SIZE:
                # Find data points in this cluster
                cluster_points = clustered_LOC_file_pref[clustered_LOC_file_pref["groupings_cluster"] == cluster_id]

                # Find closest cluster and merge
                other_clusters = clustered_LOC_file_pref[clustered_LOC_file_pref["groupings_cluster"] != cluster_id]
                closest_cluster_id = other_clusters["groupings_cluster"].mode()[0]  # Find most frequent cluster

                # Reassign small clusters
                clustered_LOC_file_pref.loc[clustered_LOC_file_pref[
                                                "groupings_cluster"] == cluster_id, "groupings_cluster"] = closest_cluster_id

        # Step 5: If Any Cluster > MAX_GROUP_SIZE, Split It
        cluster_sizes = clustered_LOC_file_pref["groupings_cluster"].value_counts()
        for cluster_id, size in cluster_sizes.items():
            if size > MAX_GROUP_SIZE:
                # Find data points in this large cluster
                large_cluster_points = clustered_LOC_file_pref[
                    clustered_LOC_file_pref["groupings_cluster"] == cluster_id]

                # Split it into smaller sub-groups
                num_new_clusters = size // AVG_GROUP_SIZE  # Keep ~AVG_GROUP_SIZE per group
                sub_kmeans = KMeans(n_clusters=num_new_clusters, random_state=42, n_init=10)
                sub_labels = sub_kmeans.fit_predict(large_cluster_points.loc[:, "SQL":"Rust"])

                # Assign new sub-clusters
                clustered_LOC_file_pref.loc[
                    clustered_LOC_file_pref["groupings_cluster"] == cluster_id, "groupings_cluster"] = sub_labels + \
                                                                                                       clustered_LOC_file_pref[
                                                                                                           "groupings_cluster"].max() + 1  # Avoid ID conflicts

        # Print Final Cluster Sizes

        clustered_LOC_file_pref["groupings_cluster"] = (
                clustered_LOC_file_pref["LOC cluster"].astype(str) + "_" +
                clustered_LOC_file_pref["Preference"].astype(str) + "_" +
                clustered_LOC_file_pref["groupings_cluster"].astype(str)
        )

        return clustered_LOC_file_pref

    #Hobbies -> Method , K Modes!
    @staticmethod
    def HOBBIES_interest(clustered_LOC_file_pref):

        Data_categorize = pd.DataFrame
        print(clustered_LOC_file_pref.columns)

        Data_categorize = clustered_LOC_file_pref.loc[:, ["General Hobbies 1", "General Hobbies 2"]]

        kmode = KModes(n_clusters=len(clustered_LOC_file_pref) / 9, init="huang", n_init=6, verbose=0)
        clustered_LOC_file_pref["groupings_cluster"] = kmode.fit_predict(Data_categorize)

        clustered_LOC_file_pref["groupings_cluster"] = (
                clustered_LOC_file_pref["LOC cluster"].astype(str) + "_" +
                clustered_LOC_file_pref["Preference"].astype(str) + "_" +
                clustered_LOC_file_pref["groupings_cluster"].astype(str)
        )

        print(clustered_LOC_file_pref)

        return clustered_LOC_file_pref


    # function purpose : create dataframe
    @staticmethod
    def Entire_DB_Merger():

        #TRISTIAN , CHANGE FILE VAR TO GET THE PANDAS DATA FRAME FROM SQL
        file_var = Data_algorithm_creation.File_insert()
        copy_of_file_var = file_var[:]

        print(file_var)

        #Step 2 : Make a K means clustering file
        Cluster_arrs, Lat_long_results = Data_algorithm_creation.Elbow_score(file_var)

        #Step 3 : create a process to help find elbow
        Optimal_cluster_numbers = Data_algorithm_creation.find_optimal_elbow(Cluster_arrs, Lat_long_results)

        #Step 4 : do clustering excel
        file_var["LOC cluster"] = Data_algorithm_creation.return_elbow_cluster_Label(file_var, Optimal_cluster_numbers)

        print(file_var)

        file_var["groupings_cluster"] = "0"

        Output_file = file_var.copy()
        Output_file = pd.DataFrame(columns=Output_file.columns)

        # Step 5 , Loop through each cluster
        for i in range(0, Optimal_cluster_numbers):
            Curr_clustered_LOC_file = file_var[file_var["LOC cluster"] == i]

            # Step 6 , loop through each preference , industry
            Curr_clustered_LOC_file_pref = Curr_clustered_LOC_file[
                Curr_clustered_LOC_file["Preference"] == "Industry interest"]
            Output_file = pd.concat(
                [Output_file, Data_algorithm_creation.industry_interest(Curr_clustered_LOC_file_pref)])

            # Step 7 , loop through each preference , learning
            Curr_clustered_LOC_file_pref = Curr_clustered_LOC_file[
                Curr_clustered_LOC_file["Preference"] == "Overall learning capability"]
            Output_file = pd.concat(
                [Output_file, Data_algorithm_creation.learning_interest(Curr_clustered_LOC_file_pref)])

            # Step 8 , loop through each preference , Hobbies
            Curr_clustered_LOC_file_pref = Curr_clustered_LOC_file[
                Curr_clustered_LOC_file["Preference"] == "Hobbies interest"]
            Output_file = pd.concat(
                [Output_file, Data_algorithm_creation.HOBBIES_interest(Curr_clustered_LOC_file_pref)])

        print("==========")
        print(Output_file)



        return Output_file

        #returns a file with the output of location ID and category cluster ID


def main():
    curr_DF = Data_algorithm_creation.Entire_DB_Merger()
    curr_DF.to_excel("clustering_results_High_defined_clustered.xlsx", index=False)
    print(curr_DF)


# Ensure this runs only when executed directly
if __name__ == "__main__":
    main()



